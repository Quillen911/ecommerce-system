<?php 

namespace App\Services\Campaigns;

use App\Models\Campaign;

use App\Repositories\Contracts\AuthenticationRepositoryInterface;
use App\Services\Campaigns\Handlers\PercentageCampaign;
use App\Services\Campaigns\Handlers\FixedCampaign;
use App\Services\Campaigns\Handlers\XBuyYPayCampaign;
use App\Traits\GetUser;
use Illuminate\Support\Facades\Log;

class CampaignManager 
{
    private $authenticationRepository;
    use GetUser;
    public function __construct(AuthenticationRepositoryInterface $authenticationRepository)
    {
        $this->authenticationRepository = $authenticationRepository;
    }

    public function resolveHandler(Campaign $campaign): ?CampaignInterface
    {
        if (! $campaign->is_active) {
            return null;
        }

        return match ($campaign->type) {
            'percentage'   => new PercentageCampaign($campaign),
            'fixed'        => new FixedCampaign($campaign),
            'x_buy_y_pay'  => new XBuyYPayCampaign($campaign),
            default        => null,
        };
    }


    public function touchUsage(Campaign $campaign): void
    {
        if ($campaign->usage_limit && $campaign->usage_count >= $campaign->usage_limit) {
            throw new \RuntimeException('Bu kampanya kullanım limitine ulaştı.');
        }

        $campaign->increment('usage_count');
    }

    public function logUsage(?int $campaignId, int $userId, ?int $orderId, int $discountAmount): void
    {
        if (! $campaignId) {
            return;
        }

        $campaign = Campaign::find($campaignId);

        if (! $campaign) {
            Log::warning('Kampanya kaydı bulunamadığı için usage loglanamadı.', [
                'campaign_id' => $campaignId,
                'user_id'     => $userId,
                'order_id'    => $orderId,
            ]);

            return;
        }

        $this->touchUsage($campaign);

        $campaign->campaign_usages()->create([
            'user_id'         => $userId,
            'order_id'        => $orderId,
            'discount_amount' => $discountAmount,
        ]);
    }
}

