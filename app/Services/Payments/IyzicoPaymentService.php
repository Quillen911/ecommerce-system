<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Models\CreditCard;
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

use Iyzipay\Model\Payment;
use Iyzipay\Model\Cancel;
use Iyzipay\Model\Refund;
use Iyzipay\Model\RefundReason;

use Iyzipay\Model\CheckoutForm;
use Iyzipay\Request\RetrieveCheckoutFormRequest;
use Iyzipay\Request\CreatePaymentRequest;
use Iyzipay\Request\CreateCancelRequest;
use Iyzipay\Request\CreateRefundRequest;

class IyzicoPaymentService implements PaymentInterface
{
    private Options $options;

    public function __construct()
    {
        $this->options = new Options();
        $this->options->setApiKey(config('services.iyzico.api_key'));
        $this->options->setSecretKey(config('services.iyzico.secret_key'));
        $this->options->setBaseUrl(config('services.iyzico.base_url'));
    }

    public function processPayment(Order $order, CreditCard $creditCard, float $amount): array
    {
        try{
            $request = new CreatePaymentRequest();

            $request->setLocale(Locale::TR);
            $request->setConversationId($order->id . '_' . time());
            $request->setCurrency(Currency::TL);
            $request->setBasketId($order->id);
            $request->setPaymentGroup(PaymentGroup::PRODUCT);
            $request->setInstallment(1);

            $paymentCard = new PaymentCard();

            $paymentCard->setCardHolderName($creditCard->card_holder_name);
            $paymentCard->setCardNumber($creditCard->card_number);
            $paymentCard->setExpireMonth($creditCard->expire_month);
            $paymentCard->setExpireYear($creditCard->expire_year);
            $paymentCard->setCvc($creditCard->cvv);
            $request->setPaymentCard($paymentCard);

            $buyer = new Buyer();

            $buyer->setId($order->user_id);
            $buyer->setName($order->user->username ?? 'Test User');
            $buyer->setSurname($order->user->surname ?? 'Test');
            $buyer->setGsmNumber($order->user->phone ?? '+905350000000');
            $buyer->setEmail($order->user->email ?? 'test@test.com');
            $buyer->setIdentityNumber('74300864791');
            $buyer->setLastLoginDate('2015-10-05 12:43:35');
            $buyer->setRegistrationDate('2013-04-21 15:12:09');
            $buyer->setRegistrationAddress('Test Mahallesi Test Sokak');
            $buyer->setIp('85.34.78.112');
            $buyer->setCity('Istanbul');
            $buyer->setCountry('Turkey');
            $request->setBuyer($buyer);

            $shippingAddress = new Address();

            $shippingAddress->setContactName('Test User');
            $shippingAddress->setCity('Istanbul');
            $shippingAddress->setCountry('Turkey');
            $shippingAddress->setAddress('Test Mahallesi Test Sokak');
            $shippingAddress->setZipCode('34732');
            $request->setShippingAddress($shippingAddress);

            $billingAddress = new Address();
            
            $billingAddress->setContactName('Test User');
            $billingAddress->setCity('Istanbul');
            $billingAddress->setCountry('Turkey');
            $billingAddress->setAddress('Test Mahallesi Test Sokak');
            $billingAddress->setZipCode('34732');
            $request->setBillingAddress($billingAddress);



            $basketItems = array();
            $totalBasketPrice = 0.0;

            foreach ($order->orderItems as $item) {
                $basketItem = new BasketItem();
                
                $basketItem->setId($item->product_id);
                $basketItem->setName($item->product->title);
                $basketItem->setCategory1($item->product->category?->category_title ?? 'Genel');
                $basketItem->setItemType(BasketItemType::PHYSICAL);
                $linePrice = round($item->product->list_price * $item->quantity, 2);
                $basketItem->setPrice(number_format($linePrice, 2, '.', ''));
                $basketItems[] = $basketItem;

                $totalBasketPrice += $linePrice;
                
            }

            $request->setBasketItems($basketItems);
            $request->setPrice(number_format($totalBasketPrice, 2, '.', ''));
            $request->setPaidPrice(number_format($amount, 2, '.', ''));

            $payment = Payment::create($request, $this->options);

            if ($payment->getStatus() === 'success') {
                $itemsTxMap = [];
                foreach ($payment->getPaymentItems() as $pItem) {
                    $itemsTxMap[$pItem->getItemId()] = $pItem->getPaymentTransactionId();

                }
                return [
                    'success' => true,
                    'payment_id' => $payment->getPaymentId(),
                    'conversation_id' => $request->getConversationId(),
                    'paid_price' => (float) $payment->getPaidPrice(),
                    'currency' => $payment->getCurrency(),
                    'payment_status' => $payment->getStatus() === 'success' ? 'paid' : 'failed',
                    'payment_transaction_id' => $itemsTxMap , 
                    'message' => 'Ödeme başarılı',
                ];
            } else {
                $errorMessage = $this->translateErrorMessage($payment->getErrorMessage(), $payment->getErrorCode());
                return [
                    'success' => false,
                    'error' => $errorMessage,
                    'error_code' => $payment->getErrorCode()
                ];
            }


            
        }catch(\Exception $e){
            Log::error('Ödeme işlemi sırasında bir hata oluştu: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Ödeme işlemi sırasında bir hata oluştu: ' . $e->getMessage()
            ];
        }
    }

    public function checkPaymentStatus(string $paymentId): array
    {
        try {
            $request = new RetrieveCheckoutFormRequest();
            $request->setToken($paymentId);

            $checkoutForm = CheckoutForm::retrieve($request, $this->options);

            if ($checkoutForm->getStatus() === 'success') {
                return [
                    'success' => true,
                    'status' => $checkoutForm->getPaymentStatus(),
                    'payment_id' => $checkoutForm->getPaymentId(),
                    'amount' => $checkoutForm->getPrice()
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $checkoutForm->getErrorMessage(),
                    'error_code' => $checkoutForm->getErrorCode()
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Ödeme durumu kontrol edilirken hata oluştu: ' . $e->getMessage()
            ];
        }
    }


    public function cancelPayment(string $paymentId): array
    {
        try {

            $request = new CreateCancelRequest();
            $ip = request()->ip() ?? '127.0.0.1';
            $request->setIp($ip);
            $request->setConversationId($paymentId);
            $request->setPaymentId($paymentId);
            $request->setLocale(Locale::TR);
        //  $request->setReason(RefundReason::OTHER);
        //  $request->setDescription("customer requested for default sample");

        $cancel = Cancel::create($request, $this->options);

        if ($cancel->getStatus() === 'success') {
            return [
                'success' => true,
                'message' => 'Ödeme iptal edildi'
            ];
        } else {
            return [
                'success' => false,
                'error' => $cancel->getErrorMessage()
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Ödeme iptal edilirken hata oluştu: ' . $e->getMessage()
            ];
        }
    }

    public function refundPayment(string $paymentTransactionId, float $amount): array
    {
        try {
            $request = new CreateRefundRequest();

            $ip = request()->ip() ?? '127.0.0.1';
            $request->setIp($ip);
            $request->setLocale(Locale::TR);
            $request->setConversationId($paymentTransactionId);
            $request->setPaymentTransactionId($paymentTransactionId);
            $request->setPrice(number_format($amount, 2, '.', ''));
            $request->setCurrency(Currency::TL);
        //  $request->setReason(RefundReason::OTHER);
        //  $request->setDescription("customer requested for default sample");

            $refund = Refund::create($request, $this->options);

            if ($refund->getStatus() === 'success') {
                return [
                    'success' => true,
                    'message' => 'Ödeme iade edildi'
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $refund->getErrorMessage()
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Ödeme iade edilirken hata oluştu: ' . $e->getMessage()
            ];
        }
    }
    
    private function translateErrorMessage(string $errorMessage, string $errorCode): string
    {
        $translations = [
            '5001' => 'Kart bilgileri hatalı. Lütfen kart numarasını, son kullanma tarihini ve CVV kodunu kontrol ediniz.',
            '5002' => 'Kart bilgileri eksik. Lütfen tüm kart bilgilerini doldurunuz.',
            '5003' => 'Kart numarası geçersiz. Lütfen kart numaranızı kontrol ediniz.',
            '5004' => 'Son kullanma tarihi geçmiş. Lütfen geçerli bir son kullanma tarihi giriniz.',
            '5005' => 'CVV kodu hatalı. Lütfen kartınızın arkasındaki 3 haneli güvenlik kodunu kontrol ediniz.',
            '5006' => 'Kart sahibi adı eksik. Lütfen kart sahibi adını giriniz.',
            '5007' => 'Kart limiti yetersiz. Lütfen kart limitinizi kontrol ediniz.',
            '5008' => 'Kart bloke edilmiş. Lütfen bankanızla iletişime geçiniz.',
            '5009' => 'Kart bilgileri doğrulanamadı. Lütfen bilgilerinizi tekrar kontrol ediniz.',
            '5010' => 'Ödeme işlemi reddedildi. Lütfen daha sonra tekrar deneyiniz.',
            '5011' => 'Kart tipi desteklenmiyor. Lütfen farklı bir kart kullanınız.',
            '5012' => '3D Secure doğrulaması başarısız. Lütfen tekrar deneyiniz.',
            '5013' => 'Kart bilgileri güvenlik kontrolünden geçemedi.',
            '5014' => 'Kart bilgileri formatı hatalı.',
            '5015' => 'Kart bilgileri eksik veya hatalı.',
        ];
        
        // Eğer hata kodu için özel çeviri varsa onu kullan
        if (isset($translations[$errorCode])) {
            return $translations[$errorCode];
        }
        
        // Genel hata mesajları için çeviri
        $generalTranslations = [
            'Invalid card information' => 'Kart bilgileri hatalı',
            'Card number is invalid' => 'Kart numarası geçersiz',
            'Expiry date is invalid' => 'Son kullanma tarihi geçersiz',
            'CVV is invalid' => 'CVV kodu hatalı',
            'Card holder name is required' => 'Kart sahibi adı gerekli',
            'Insufficient funds' => 'Yetersiz bakiye',
            'Card is blocked' => 'Kart bloke edilmiş',
            'Payment declined' => 'Ödeme reddedildi',
            '3D Secure verification failed' => '3D Secure doğrulaması başarısız',
        ];
        
        foreach ($generalTranslations as $english => $turkish) {
            if (stripos($errorMessage, $english) !== false) {
                return $turkish;
            }
        }
        
        // Eğer çeviri bulunamazsa orijinal mesajı döndür
        return $errorMessage ?: 'Kart bilgileri hatalı. Lütfen bilgilerinizi kontrol ediniz.';
    }
}
