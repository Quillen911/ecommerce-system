<?php

namespace App\Services\Payments\Providers;

use App\Models\CheckoutSession;
use App\Models\PaymentMethod;
use App\Models\PaymentProvider;
use App\Models\User;
use App\Services\Payments\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Facades\Log;

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
            $paymentCard->setCardNumber($data['card_number']);
            $paymentCard->setExpireMonth($data['expire_month']);
            $paymentCard->setExpireYear($data['expire_year']);
            $paymentCard->setCvc($data['cvv']);
        }
        $request->setPaymentCard($paymentCard);

        $shipping = $session->shipping_data;
        $billing  = $session->billing_data ?? $shipping;

        $buyer = new Buyer();
        $buyer->setId((string) $user->id);
        $buyer->setName($user->first_name);
        $buyer->setSurname($user->last_name);
        $buyer->setGsmNumber($user->phone);
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
        $billingAddress->setContactName($shipping['first_name'] ?? 'Test');
        $billingAddress->setCity($shipping['city'] ?? 'İstanbul');
        $billingAddress->setCountry($shipping['country'] ?? 'Türkiye');
        $billingAddress->setAddress($shipping['address_line_1'] ?? 'Adres');
        $billingAddress->setZipCode($shipping['postal_code'] ?? '34000');
        $request->setBillingAddress($billingAddress);

        $basketItems = [];
        $total = 0;

        foreach ($session->bag_snapshot['items'] as $item) {
            
            $price = $item['total_price_cents'] / 100;
            if ($price <= 0) {
                continue;
            }

            $basketItem = new BasketItem();
            $basketItem->setId((string) $item['bag_item_id']);
            $basketItem->setName($item['product_title']);
            $basketItem->setCategory1('Genel');
            $basketItem->setItemType(BasketItemType::PHYSICAL);
            $basketItem->setPrice(number_format($price, 2, '.', ''));

            $basketItems[] = $basketItem;
            $total += $price;
        }

        $request->setBasketItems($basketItems);
        $request->setPrice(number_format($total, 2, '.', ''));
        $request->setPaidPrice(number_format($total, 2, '.', ''));
        $request->setCallbackUrl('https://nonseriately-uncoded-elba.ngrok-free.dev/api/proxy/iyzico-callback');



        $initialize = ThreedsInitialize::create($request, $this->options);

        if ($initialize->getStatus() !== 'success') {
            throw new \RuntimeException(
                $initialize->getErrorMessage() ?? 'Ödeme isteği oluşturulamadı.',
                (int) $initialize->getErrorCode()
            );
        }

        return [
            'provider'          => $this->provider->code,
            'payment_intent_id' => $initialize->getPaymentId(),
            'conversation_id'   => $initialize->getConversationId(),
            'amount_cents'      => $session->bag_snapshot['totals']['final_cents'],
            'currency'          => 'TRY',
            'status'            => $initialize->getStatus(),
            'requires_3ds'      => true,
            'three_ds_html'     => $initialize->getHtmlContent(),
            'raw'               => $initialize->getRawResult(),
        ];
    }


    public function confirmPayment(CheckoutSession $session, array $payload): array
    {
        \Log::debug('Iyzico confirm payload', $payload);
        if (($payload['mdStatus'] ?? null) !== '1') {
            throw new \RuntimeException('3D doğrulama başarısız.');

        }

        $request = new CreateThreedsPaymentRequest();
        $request->setLocale(Locale::TR);
        $request->setConversationId($payload['conversation_id']);
        $request->setPaymentId($payload['payment_intent_id']);
        $request->setConversationData($payload['conversationData'] ?? null);

        $payment = ThreedsPayment::create($request, $this->options);

        if ($payment->getStatus() !== 'success') {
            throw new \RuntimeException(
                $payment->getErrorMessage() ?? '3D ödeme tamamlanamadı.',
                (int) $payment->getErrorCode()
            );
        }
        \Log::debug('Iyzico confirm payload', $payload);
        return [
            'status'                  => 'authorized',
            'payment_id'              => $payment->getPaymentId(),
            'conversation_id'         => $payload['conversation_id'],
            'authorized_amount_cents' => $payment->getPaidPrice(),
            'currency'                => $payment->getCurrency(),
            'raw'                     => $payment->getRawResult(),
        ];
    }

    public function storePaymentMethod(
        User $user,
        PaymentMethod $method,
        array $gatewayPayload = []
    ): PaymentMethod {
        
        $request = new CreateCardRequest();
        $request->setLocale(Locale::TR);
        $request->setConversationId('card_' . $user->id . '_' . time());
        $request->setEmail($cardData['email'] ?? 'test@test.com');
        $request->setExternalId((string) $user->id);

        $cardInformation = new CardInformation();
        $cardInformation->setCardAlias($cardData['card_alias'] ?? 'Kredi Kartım');
        $cardInformation->setCardHolderName($cardData['card_holder_name']);
        $cardInformation->setCardNumber($cardData['card_number']);
        $cardInformation->setExpireMonth($cardData['expire_month']);
        $cardInformation->setExpireYear($cardData['expire_year']);
        $request->setCard($cardInformation);

        $card = Card::create($request, $this->options);

        if ($card->getStatus() !== 'success') {
            throw new \RuntimeException($card->getErrorMessage() ?: 'Kart kaydedilemedi.', $card->getErrorCode());
        }

        $method->fill([
            'provider_customer_id'       => $card->getCardUserKey(),
            'provider_payment_method_id' => $card->getCardToken(),
            'metadata'                  => null,
        ])->save();

        return $method;
        
    }
}
