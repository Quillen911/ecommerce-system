<?php

namespace App\Services\Bag\Services;

use App\Services\Bag\Contracts\BagInterface;
use App\Exceptions\InsufficientStockException;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
use App\Repositories\Contracts\Bag\BagRepositoryInterface;
use App\Repositories\Contracts\Campaign\CampaignRepositoryInterface;
use App\Services\Campaigns\CampaignManager;
use Illuminate\Validation\ValidationException;
use Throwable;

use App\Traits\GetUser;

class BagService implements BagInterface
{
    use GetUser;

    public function __construct(
        private readonly StockService $stockService, 
        private readonly BagCalculationService $bagCalculationService, 
        private readonly AuthenticationRepositoryInterface $authenticationRepository, 
        private readonly BagRepositoryInterface $bagRepository,
        private readonly CampaignRepositoryInterface $campaignRepository,
        private readonly CampaignManager $campaignManager,
    ) {
    }

    public function getBag()
    {
        $user = $this->getUser();
        $bag  = $this->bagRepository->getBag($user);

        if (! $bag) {
            throw new \RuntimeException('Sepet bulunamadı!');
        }

        $bag->load('campaign', 'campaign.campaignProducts', 'campaign.campaignCategories');

        $bagItems = $bag->bagItems()
            ->with([
                'variantSize.productVariant.variantImages',
                'variantSize.sizeOption',
                'variantSize.productVariant.product.category',
                'variantSize.inventory',
            ])
            ->orderBy('id')
            ->get();
        $bagItems = $this->checkProductAvailability($bagItems, $bag);

        if ($bagItems->isEmpty()) {
            throw new \Exception('Sepet Boş!');
        }

        $total = $this->bagCalculationService->calculateTotal($bagItems);
        $cargo = $this->bagCalculationService->calculateCargoPrice($total);
        $discount = $bag->campaign_discount_cents ?? 0;
        $final = max($total + $cargo - $discount, 0);

        $discount = 0;
        $discountItems = collect();
        $appliedCampaign = null;
        
        if ($bag->campaign) {
            $campaign = $bag->campaign->load('campaignProducts', 'campaignCategories');
            $handler  = $this->campaignManager->resolveHandler($campaign);

            try {
                if ($handler && $handler->isApplicable($bagItems->all())) {
                    $calcResult     = $handler->calculateDiscount($bagItems->all());
                    $discount       = $calcResult['discount_cents'] ?? 0;
                    $discountItems  = collect($calcResult['items'] ?? []);
                    $appliedCampaign = $campaign;

                    if ($bag->campaign_discount_cents !== $discount) {
                        $bag->update(['campaign_discount_cents' => $discount]);
                    }
                } else {
                    $this->detachCampaign($bag);
                }
            } catch (ValidationException $e) {
                $this->detachCampaign($bag);
            } catch (Throwable $e) {
                report($e);              // loglayıp kampanyayı düşürüyoruz
                $this->detachCampaign($bag);
            }
        } else {
            $bag->update(['campaign_discount_cents' => 0]);
        }

        
        $final = max($total + $cargo - $discount, 0);
        
        return [
            'products'         => $bagItems,
            'applied_campaign' => $appliedCampaign,
            'total_cents'      => $total,
            'cargo_price_cents'=> $cargo,
            'discount_cents'   => $discount,
            'final_price_cents'=> $final,
            'discount_items'   => $discountItems,
            'campaigns'        => $this->allCampaigns(),
        ];
        
    }

    public function addToBag($variantSizeId, $quantity = 1)
    {
        try {
            $user = $this->getUser();
            if(!$user){
                throw new \Exception('Kullanıcı bulunamadı!');
            }
            $bag = $this->bagRepository->createBag($user);
            if(!$bag){
                throw new \Exception('Sepet bulunamadı!');
            }
            $productItem = $this->stockService->checkStockAvailability($bag, $variantSizeId, $quantity);
            
            $a = $this->stockService->reserveStock($productItem['itemInTheBag'], $productItem['stock'], $bag, $variantSizeId, $quantity);
            return $a;
        } catch (InsufficientStockException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function selectCampaign(int $campaignId): array
    {
        $user = $this->getUser();
        if (! $user) {
            throw new \RuntimeException('Kullanıcı bulunamadı!');
        }

        $bag = $this->bagRepository->getBag($user);
        if (! $bag) {
            throw new \RuntimeException('Sepet bulunamadı!');
        }

        $bagItems = $bag->bagItems()
            ->with([
                'variantSize.productVariant.variantImages',
                'variantSize.productVariant.product.category',
                'variantSize.sizeOption',
                'variantSize.inventory',
            ])
            ->get();


        if ($bagItems->isEmpty()) {
            throw new \RuntimeException('Sepetiniz boş, kampanya uygulanamaz.');
        }

        $campaign = $this->campaignRepository
            ->getActiveCampaign($campaignId);
        if (!$campaign) {
            throw new \RuntimeException('Seçili kampanya artık aktif değil veya bulunamadı.');
        }
        $campaign->load(['campaignProducts', 'campaignCategories']);

        $handler = $this->campaignManager->resolveHandler($campaign);

        if (! $handler || ! $handler->isApplicable($bagItems->all())) {
            throw ValidationException::withMessages([
                'campaign' => ['Bu kampanya sepetiniz için uygun değil.'],
            ]);
        }

        $result   = $handler->calculateDiscount($bagItems->all());
        $discount = $result['discount_cents'] ?? 0;

        $total = $this->bagCalculationService->calculateTotal($bagItems);
        $cargo = $this->bagCalculationService->calculateCargoPrice($total);
        $final = max($total + $cargo - $discount, 0);

        $bag->update([
            'campaign_id'             => $campaign->id,
            'campaign_discount_cents' => $discount,
        ]);

        return [
            'products'          => $bagItems,
            'applied_campaign'  => $bag->campaign,
            'total_cents'       => $total,
            'cargo_price_cents' => $cargo,
            'discount_cents'    => $discount,
            'final_price_cents' => $final,
            'discount_items'    => $result['items'] ?? collect(),
        ];
    }

    public function unSelectCampaign(): array
    {
        $user = $this->getUser();
        $bag  = $this->bagRepository->getBag($user);

        if (! $bag || ! $bag->campaign_id) {
            throw new \RuntimeException('Sepetinizde kaldırılacak kampanya yok.');
        }

        $bag->update([
            'campaign_id'             => null,
            'campaign_discount_cents' => 0,
        ]);

        $bagItems = $bag->bagItems()->with('variantSize.productVariant.variantImages', 'variantSize.productVariant.product.category')->get();
        $bagItems = $this->checkProductAvailability($bagItems, $bag);

        $total = $this->bagCalculationService->calculateTotal($bagItems);
        $cargo = $this->bagCalculationService->calculateCargoPrice($total);
        $final = max($total + $cargo, 0);

        return [
            'products'              => $bagItems,
            'appliedCampaign'       => null,
            'total_cents'           => $total,
            'total'                 => $total / 100,
            'cargoPrice_cents'      => $cargo,
            'cargoPrice'            => $cargo / 100,
            'discount_cents'        => 0,
            'discount'              => 0,
            'finalPrice_cents'      => $final,
            'finalPrice'            => $final / 100,
        ];
    }

    public function allCampaigns()
    {
        return $this->campaignRepository->getActiveCampaigns();
    }

    public function showBagItem($bagItemId)
    {
        $user = $this->getUser();
        $bag = $this->bagRepository->getBag($user);
        if(!$bag){
            return null;
        }
        return $bag->bagItems()
                ->with('variantSize.productVariant.product')
                ->where('id', $bagItemId)
                ->where('bag_id', $bag->id)
                ->first();
    }

    public function updateBagItem($bagItemId, $quantity)
    {
        $bagItem = $this->showBagItem($bagItemId);
        if($bagItem){
            $bagItem->quantity = $quantity;
            $bagItem->save();
            return $bagItem;
        }
        else{
            return ['error' => 'Ürün bulunamadı!'];
        }
    }

    public function destroyBagItem($bagItemId)
    {
        $bagItem = $this->showBagItem($bagItemId);
        if($bagItem){
            $bagItem->delete();
            return ['success' => true, 'message' => 'Ürün sepetten kaldırıldı.'];
        }
        else{
            return ['error' => 'Ürün bulunamadı!'];
        }
    }
    private function checkProductAvailability($bagItems, $bag)
    {
        
        $bagItems = $bagItems->filter(function($item) use ($bag) {
            if (!$item->product || $item->product->deleted_at !== null) {
                if(!$item->variantSize->productVariant || $item->variantSize->productVariant->deleted_at !== null){
                    $item->delete();
                    return false;
                }
            }

            if($item->variantSize->inventory->available <= 0){
                $item->delete();
                return false;
            }
            return true;
        });
        return $bagItems;
    }

    private function detachCampaign($bag): void
    {
        $bag->update([
            'campaign_id'             => null,
            'campaign_discount_cents' => 0,
        ]);
        $bag->unsetRelation('campaign');
    }


}