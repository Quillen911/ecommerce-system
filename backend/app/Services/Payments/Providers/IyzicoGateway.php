<?php

namespace App\Services\Payments\Providers;

use App\Models\CheckoutSession;
use App\Models\PaymentMethod;
use App\Models\PaymentProvider;
use App\Models\User;
use App\Services\Payments\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

//Iyzipay kütüphanesi
use Iyzipay\Options;

use Iyzipay\Model\Locale;
use Iyzipay\Model\Currency;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\PaymentCard;
use Iyzipay\Model\PaymentGroup;
use Iyzipay\Model\BasketItemType;
use Iyzipay\Model\SubMerchant;
use Iyzipay\Model\Payment;
use Iyzipay\Model\Cancel;
use Iyzipay\Model\Refund;
use Iyzipay\Model\RefundReason;
use Iyzipay\Model\Card;
use Iyzipay\Model\CardInformation;
use Iyzipay\Model\CheckoutForm;
use Iyzipay\Model\ThreedsInitialize;
use Iyzipay\Model\ThreedsPayment;

use Iyzipay\Request\CreatePaymentRequest;
use Iyzipay\Request\CreateCancelRequest;
use Iyzipay\Request\CreateRefundRequest;
use Iyzipay\Request\CreateSubMerchantRequest;
use Iyzipay\Request\CreateThreedsPaymentRequest;
use Iyzipay\Request\CreateCardRequest;

class IyzicoGateway implements PaymentGatewayInterface
{
    private Options $options;
    public function __construct(private readonly PaymentProvider $provider)
    {
        $this->options = new Options();
        $this->options->setApiKey(config('services.iyzico.api_key'));
        $this->options->setSecretKey(config('services.iyzico.secret_key'));
        $this->options->setBaseUrl(config('services.iyzico.base_url'));
    }

    public function buildTemporaryMethod(User $user, array $data): PaymentMethod
    {
        return new PaymentMethod([
            'user_id'          => $user->id,
            'provider'         => $this->provider->code,
            'type'             => 'card',
            'brand'            => $data['brand'] ?? null,
            'last4'            => substr($data['card_number'], -4),
            'exp_month'        => $data['expire_month'],
            'exp_year'         => $data['expire_year'],
            'card_holder_name' => $data['card_holder_name'],
            'card_alias'       => $data['card_alias'] ?? 'Anonim',
            'is_active'        => true,
        ]);
    }

    public function processPayment(
        User $user,
        CheckoutSession $session,
        PaymentMethod $method,
        array $data
    ): array {
        
        $request = new CreatePaymentRequest();

        $request->setLocale(Locale::TR);
        $conversationId = 'session_' . $session->id . '_' . time();
        $request->setConversationId($conversationId);
        $request->setCurrency(Currency::TL);
        $request->setBasketId((string) $session->id);
        $request->setPaymentGroup(PaymentGroup::PRODUCT);
        $request->setInstallment($data['installment'] ?? 1);

        $paymentCard = new PaymentCard();
        if ($method->provider_payment_method_id && $method->provider_customer_id) {
            $paymentCard->setCardToken($method->provider_payment_method_id);
            $paymentCard->setCardUserKey($method->provider_customer_id);
        } else {
            $paymentCard->setCardHolderName($data['card_holder_name']);
            $paymentCard->setCardAlias($data['card_alias'] ?? 'Anonim');
            $paymentCard->setCardNumber($data['card_number']);
            $paymentCard->setExpireMonth($data['expire_month']);
            $paymentCard->setExpireYear($data['expire_year']);
            $paymentCard->setCvc($data['cvv']);
            $paymentCard->setRegisterCard($data['save_card'] ? 1 : 0);
        }
        $request->setPaymentCard($paymentCard);

        $shipping = $session->shipping_data;
        $billing  = $session->billing_data ?? $shipping;

        $buyer = new Buyer();
        $buyer->setId((string) $user->id);
        $buyer->setName($user->first_name);
        $buyer->setSurname($user->last_name);
        $buyer->setGsmNumber('+90' . ltrim($user->phone, '0'));
        $buyer->setEmail($user->email);
        $buyer->setIdentityNumber('74300864791');
        $buyer->setLastLoginDate(now()->subDays(1)->format('Y-m-d H:i:s'));
        $buyer->setRegistrationDate($user->created_at?->format('Y-m-d H:i:s') ?? now()->format('Y-m-d H:i:s'));
        $buyer->setRegistrationAddress($shipping['address_line_1'] ?? 'Adres');
        $buyer->setIp(request()->ip() ?? '127.0.0.1');
        $buyer->setCity($shipping['city'] ?? 'İstanbul');
        $buyer->setCountry($shipping['country'] ?? 'Türkiye');
        $request->setBuyer($buyer);

        $shippingAddress = new Address();
        $shippingAddress->setContactName($shipping['first_name'] ?? 'Test');
        $shippingAddress->setCity($shipping['city'] ?? 'İstanbul');
        $shippingAddress->setCountry($shipping['country'] ?? 'Türkiye');
        $shippingAddress->setAddress($shipping['address_line_1'] ?? 'Adres');
        $shippingAddress->setZipCode($shipping['postal_code'] ?? '34000');
        $request->setShippingAddress($shippingAddress);

        $billingAddress = new Address();
        $billingAddress->setContactName($billing['first_name'] ?? 'Test');
        $billingAddress->setCity($billing['city'] ?? 'İstanbul');
        $billingAddress->setCountry($billing['country'] ?? 'Türkiye');
        $billingAddress->setAddress($billing['address_line_1'] ?? 'Adres');
        $billingAddress->setZipCode($billing['postal_code'] ?? '34000');
        $request->setBillingAddress($billingAddress);

        $basketItems = [];
        $total = 0;
        $discounts = collect($session->bag_snapshot['applied_campaign']['discount_items'] ?? [])
            ->keyBy('bag_item_id');

        foreach ($session->bag_snapshot['items'] as $item) {
            $bagItemId = $item['bag_item_id'];
            $discount  = $discounts->get($bagItemId);
            $paidPriceCents = $discount['discounted_total_cents'] ?? $item['total_price_cents'];
            $price = $paidPriceCents / 100;

            if ($price <= 0) {
                continue;
            }

            $basketItem = new BasketItem();
            $basketItem->setId((string) $item['bag_item_id']);
            $basketItem->setName($item['product_title']);
            $basketItem->setCategory1('Genel');
            $basketItem->setItemType(BasketItemType::PHYSICAL);
            $basketItem->setPrice(sprintf('%.2f', $price));

            $basketItems[] = $basketItem;
            $total += $price;
        }
        
        $shippingCost = ($session->bag_snapshot['totals']['cargo_cents'] ?? 0) / 100;
        if ($shippingCost > 0) {
            $shippingItem = new BasketItem();
            $shippingItem->setId('shipping');
            $shippingItem->setName('Kargo Ücreti');
            $shippingItem->setCategory1('Kargo');
            $shippingItem->setItemType(BasketItemType::PHYSICAL);
            $shippingItem->setPrice(sprintf('%.2f', $shippingCost));

            $basketItems[] = $shippingItem;
            $total        += $shippingCost;
        }
    
        $request->setBasketItems($basketItems);
        $request->setPrice(sprintf('%.2f', $total));

        $finalPrice = $session->bag_snapshot['totals']['final_cents'] / 100;
        
        $request->setPaidPrice(sprintf('%.2f', $finalPrice));
        $request->setCallbackUrl('https://nonseriately-uncoded-elba.ngrok-free.dev/api/proxy/iyzico-callback');


        if (!empty($data['requires_3ds']) && $data['requires_3ds'] === true) {
            
            $initialize = ThreedsInitialize::create($request, $this->options);

            if ($initialize->getStatus() !== 'success') {
                throw new \RuntimeException(
                    $payload['localeMessage'] ?? $payload['message'] ?? $payload['errorMessage'] ?? '3D ödeme isteği oluşturulamadı.',
                    (int) $initialize->getErrorCode()
                );
            }

            return [
                'provider'        => $this->provider->code,
                'payment_id'      => $initialize->getPaymentId(),
                'conversation_id' => $initialize->getConversationId(),
                'amount_cents'    => $session->bag_snapshot['totals']['final_cents'],
                'card'            => null,
                'currency'        => 'TRY',
                'status'          => $initialize->getStatus(),
                'requires_3ds'    => true,
                'three_ds_html'   => $initialize->getHtmlContent(),
                'raw'             => $initialize->getRawResult(),
            ];
           
        }
         else {
            $payment = Payment::create($request, $this->options);

            if ($payment->getStatus() !== 'success') {
                throw new \RuntimeException(
                    $payment->getErrorMessage() ?? 'Ödeme başarısız.',
                    (int) $payment->getErrorCode()
                );
            }

            $itemTransactions = [];
            foreach ($payment->getPaymentItems() as $item) {
                $itemTransactions[$item->getItemId()] = $item->getPaymentTransactionId();
                $itemPrices[$item->getItemId()]  = $item->getPrice(); // Iyzi’de kayıtlı basket price
            }
            Log::info('Iyzi payment item prices', [
                'session_id' => $session->id,
                'tx_map'     => $itemTransactions,
                'price_map'  => $itemPrices,
                'basket_items'    => $request->getBasketItems(),
                'basket_price'    => $request->getPrice(),
            ]);
            return [
                'provider'               => $this->provider->code,
                'payment_id'             => $payment->getPaymentId(),
                'conversation_id'        => $payment->getConversationId(),
                'payment_transaction_id' => $itemTransactions,
                'amount_cents'           => $session->bag_snapshot['totals']['final_cents'],
                'currency'               => 'TRY',
                'status'                 => 'success',
                'requires_3ds'           => false,
                'raw'                    => $payment->getRawResult(),
            ];
        }
    }


    public function confirmPayment(CheckoutSession $session, array $payload): array
    {
        if ($payload['mdStatus'] !== "1") {
            throw new \RuntimeException('3D doğrulama başarısız.');
        }


        $request = new CreateThreedsPaymentRequest();
        $request->setLocale(Locale::TR);
        $conversationId = $payload['conversationId'] ?? ($session->payment_data['intent']['conversation_id'] ?? null);
        $paymentId = $payload['paymentId'] ?? ($session->payment_data['intent']['payment_id'] ?? null);

        if (!$paymentId || !$conversationId) {
            throw new \RuntimeException('Hatalı Iyzico callback oturum bilgileri.');

        }

        $request->setConversationId($conversationId);
        $request->setPaymentId($paymentId);

        if (!empty($payload['conversationData'])) {
            $request->setConversationData($payload['conversationData']);
        }
        

        $payment = ThreedsPayment::create($request, $this->options);
        
        $cardDetails = [
            'type'        => $payment->getCardType(),        // CREDIT_CARD, DEBIT_CARD
            'association' => $payment->getCardAssociation(), // VISA, MASTER_CARD
            'family'      => $payment->getCardFamily(),      // Bonus, World vb.
        ];


        if ($payment->getStatus() !== 'success') {
            throw new \RuntimeException(
                $payment->getErrorMessage() ?? '3D ödeme tamamlanamadı.',
                (int) $payment->getErrorCode()
            );
        }
        $itemTransactions = [];
        foreach ($payment->getPaymentItems() as $item) {
            $itemTransactions[$item->getItemId()] = $item->getPaymentTransactionId();
        }

        return [
            'status'                  => 'success',
            'payment_id'              => $payment->getPaymentId(),
            'conversation_id'         => $payment->getConversationId(),
            'authorized_amount_cents' => (int) ($payment->getPaidPrice() * 100),
            'card'                    => $cardDetails,
            'currency'                => $payment->getCurrency(),
            'payment_transaction_id'  => $itemTransactions,
            'raw'                     => $payment->getRawResult(),
        ];
    }

    public function refundPayment($transactionId, $amountCents, $payload): array
    {
        $request = new CreateRefundRequest();
        $request->setLocale(Locale::TR);
        $request->setConversationId('refund_' . time());
        $request->setCurrency(Currency::TL);
        $request->setPaymentTransactionId($transactionId);
        $request->setPrice(number_format($amountCents / 100, 2, '.', ''));
        $request->setReason(RefundReason::OTHER);
        $request->setDescription($payload['reason']);
        $refund = Refund::create($request, $this->options);

        if ($refund->getStatus() !== 'success') {
            Log::info([
                'message' => 'İade işlemi başarısız',
                'refund' => $refund,
                'payload' => $payload,
                'amountCents' => $amountCents,
                'transactionId' => $transactionId,
                'message' => $refund->getErrorMessage(),
                'code' => $refund->getErrorCode(),
            ]);
            throw new \RuntimeException($refund->getErrorMessage() ?: 'İade işlemi başarısız.', $refund->getErrorCode());
        }
        return [
            'message' => 'İade işlemi başarılı',
            'refund' => $refund
        ];
    }

    public function storePaymentMethod(User $user, $method, $payload ,array $data): PaymentMethod 
    {
        $request = new CreateCardRequest();
        $request->setLocale(Locale::TR);
        $request->setConversationId('card_' . $user->id . '_' . time());
        $request->setEmail($user->email);
        $request->setExternalId((string) $user->id);
        $cardInformation = new CardInformation();

        $cardInformation->setCardAlias($data['card_alias'] ?? 'Kredi Kartım');
        $cardInformation->setCardHolderName($data['card_holder_name']);
        $cardInformation->setCardNumber($data['card_number']);
        $cardInformation->setExpireMonth($data['expire_month']);
        $cardInformation->setExpireYear($data['expire_year']);
        $request->setCard($cardInformation);

        $card = Card::create($request, $this->options);
        if ($card->getStatus() !== 'success') {
            throw new \RuntimeException($card->getErrorMessage() ?: 'Kart kaydedilemedi.', $card->getErrorCode());
        }

        $method->fill([
            'provider_customer_id'       => $card->getCardUserKey(),
            'provider_payment_method_id' => $card->getCardToken(),
            'metadata'               =>array_merge(
                $method->metadata ?? [],
                [
                    'card_family' => $payload['card']['family'] ?? null
                ] 
            ),
        ])->save();

        return $method;
        
    }
}
