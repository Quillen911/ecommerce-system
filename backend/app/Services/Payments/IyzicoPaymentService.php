<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Models\CreditCard;
use App\Models\Store;
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

use Iyzipay\Model\CheckoutForm;
use Iyzipay\Request\RetrieveCheckoutFormRequest;
use Iyzipay\Request\CreatePaymentRequest;
use Iyzipay\Request\CreateCancelRequest;
use Iyzipay\Request\CreateRefundRequest;
use Iyzipay\Request\CreateSubMerchantRequest;

use Iyzipay\Model\Card;
use Iyzipay\Model\CardInformation;
use Iyzipay\Request\CreateCardRequest;

//kapalı
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


    public function createCardToken(array $cardData, $userId): array
    {
        try {
            $request = new CreateCardRequest();
            $request->setLocale(Locale::TR);
            $request->setConversationId('card_' . $userId . '_' . time());
            $request->setEmail($cardData['email'] ?? 'test@test.com');
            $request->setExternalId((string) $userId);

            $cardInformation = new CardInformation();
            $cardInformation->setCardAlias($cardData['card_alias'] ?? 'Kredi Kartım');
            $cardInformation->setCardHolderName($cardData['card_holder_name']);
            $cardInformation->setCardNumber($cardData['card_number']);
            $cardInformation->setExpireMonth($cardData['expire_month']);
            $cardInformation->setExpireYear($cardData['expire_year']);
            $request->setCard($cardInformation);

            $card = Card::create($request, $this->options);

            if ($card->getStatus() === 'success') {
                return [
                    'success' => true,
                    'card_token' => $card->getCardToken(),
                    'card_user_key' => $card->getCardUserKey(),
                    'message' => 'Kart başarıyla kaydedildi'
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $card->getErrorMessage(),
                    'error_code' => $card->getErrorCode()
                ];
            }

        } catch (\Exception $e) {
            Log::error('İyzico kart token oluşturma hatası: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Kart token oluşturulurken hata oluştu: ' . $e->getMessage()
            ];
        }
    }

    public function processPayment(Order $order, CreditCard $creditCard, float $amount, array $tempCardData = null): array
    {
        try{

            $request = new CreatePaymentRequest();

            $request->setLocale(Locale::TR);
            $request->setConversationId((string) $order->id . '_' . time());
            $request->setCurrency(Currency::TL);
            $request->setBasketId((string) $order->id);
            $request->setPaymentGroup(PaymentGroup::PRODUCT);
            $request->setInstallment(1);

            if ($creditCard->iyzico_card_token && $creditCard->iyzico_card_user_key) {

                $paymentCard = new PaymentCard();
                $paymentCard->setCardToken($creditCard->iyzico_card_token);
                $paymentCard->setCardUserKey($creditCard->iyzico_card_user_key);
                $request->setPaymentCard($paymentCard);
                
            } else {

                if (!$tempCardData || !isset($tempCardData['card_number']) || !isset($tempCardData['cvv'])) {
                    throw new \Exception('İlk ödeme için kart numarası ve CVV gerekli.');
                }
                
                $paymentCard = new PaymentCard();
                $paymentCard->setCardHolderName($creditCard->card_holder_name);
                $paymentCard->setCardNumber($tempCardData['card_number']);
                $paymentCard->setExpireMonth($creditCard->expire_month);
                $paymentCard->setExpireYear($creditCard->expire_year);
                $paymentCard->setCvc($tempCardData['cvv']);
                $request->setPaymentCard($paymentCard);
            }

            $buyer = new Buyer();

            $buyer->setId((string) $order->user_id);
            $buyer->setName($order->user->username ?? 'Test User');
            $buyer->setSurname($order->user->surname ?? 'Test');
            $buyer->setGsmNumber($order->user->phone ?? '+905350000000');
            $buyer->setEmail($order->user->email ?? 'test@test.com');
            $buyer->setIdentityNumber('74300864791');
            $buyer->setLastLoginDate('2015-10-05 12:43:35');
            $buyer->setRegistrationDate('2013-04-21 15:12:09');
            $buyer->setRegistrationAddress($order->shippingAddress->address_line_1);
            $buyer->setIp('85.34.78.112');
            $buyer->setCity($order->shippingAddress->city);
            $buyer->setCountry($order->shippingAddress->country);
            $request->setBuyer($buyer);

            $shippingAddress = new Address();

            $shippingAddress->setContactName($order->shippingAddress->first_name);
            $shippingAddress->setCity($order->shippingAddress->city);
            $shippingAddress->setCountry($order->shippingAddress->country);
            $shippingAddress->setAddress($order->shippingAddress->address_line_1);
            $shippingAddress->setZipCode($order->shippingAddress->postal_code);
            $request->setShippingAddress($shippingAddress);

            $billingAddress = new Address();
            
            $billingAddress->setContactName($order->billingAddress->first_name);
            $billingAddress->setCity($order->billingAddress->city);
            $billingAddress->setCountry($order->billingAddress->country);
            $billingAddress->setAddress($order->billingAddress->address_line_1);
            $billingAddress->setZipCode($order->billingAddress->postal_code);
            $request->setBillingAddress($billingAddress);

            $basketItems = array();
            $totalBasketPrice = 0.0;

            foreach ($order->orderItems as $item) {
                /*$product = $item->product;
                $store = $product->store;

                if (!$store->sub_merchant_key) {
                    throw new \Exception('Mağaza alt üye iş yeri oluşturulmamış');
                }*/

                $basketItem = new BasketItem();
                
                $basketItem->setId((string) $item->product_id);
                $basketItem->setName($item->product->title);
                $basketItem->setCategory1($item->product->category?->category_title ?? 'Genel');
                $basketItem->setItemType(BasketItemType::PHYSICAL);
                $linePrice = round((float)($item->paid_price), 4);
                
                // Sıfır fiyatlı ürünleri Iyzico'ya gönderme (kampanya ürünleri)
                if ($linePrice <= 0) {
                    continue; // Bu ürünü atla
                }
                
                $basketItem->setPrice(number_format($linePrice, 4, '.', ''));

               // $basketItem->setSubMerchantKey($store->sub_merchant_key);
                //$basketItem->setSubMerchantPrice(number_format($linePrice, 2, '.', ''));

                $basketItems[] = $basketItem;

                $totalBasketPrice += $linePrice;
                
            }

            $request->setBasketItems($basketItems);
            $request->setPrice(number_format($totalBasketPrice, 2, '.', ''));
            $request->setPaidPrice(number_format($amount, 2, '.', ''));
            
            $payment = Payment::create($request, $this->options);

            if ($payment->getStatus() === 'success') {
                
                
                if (!$creditCard->iyzico_card_token && $tempCardData) {
                    
                    $tokenData = [
                        'card_holder_name' => $creditCard->card_holder_name,
                        'card_number' => $tempCardData['card_number'],
                        'expire_month' => $creditCard->expire_month,
                        'expire_year' => $creditCard->expire_year,
                        'card_alias' => $creditCard->name,
                        'email' => $order->user->email ?? 'test@test.com'
                    ];
                    
                    $tokenResult = $this->createCardToken($tokenData, $order->user_id);
                    
                    if ($tokenResult['success']) {
                        $creditCard->update([
                            'iyzico_card_token' => $tokenResult['card_token'],
                            'iyzico_card_user_key' => $tokenResult['card_user_key']
                        ]);
                        Log::info('İyzico kart token başarıyla kaydedildi', [
                            'credit_card_id' => $creditCard->id,
                            'user_id' => $order->user_id
                        ]);
                    } else {
                        Log::error('İyzico kart token kaydetme hatası', [
                            'credit_card_id' => $creditCard->id,
                            'error' => $tokenResult['error']
                        ]);
                    }
                }
                
                $itemsTxMap = [];
                foreach ($payment->getPaymentItems() as $pItem) {
                    $itemsTxMap[$pItem->getItemId()] = $pItem->getPaymentTransactionId();
                }
                $response = [
                    'success' => true,
                    'payment_id' => (int) $payment->getPaymentId(),
                    'conversation_id' => $request->getConversationId(),
                    'paid_price' => (float) $payment->getPaidPrice(),
                    'currency' => $payment->getCurrency(),
                    'payment_status' => $payment->getStatus() === 'success' ? 'paid' : 'failed',
                    'payment_transaction_id' => $itemsTxMap, 
                    'message' => 'Ödeme başarılı',
                ];
                
                
                if (isset($tokenResult) && $tokenResult['success']) {
                    $response['card_token'] = $tokenResult['card_token'];
                    $response['card_user_key'] = $tokenResult['card_user_key'];
                }
                Log::info('Ödeme işlemi başarılı', $response);
                return $response;
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
        
        
        if (isset($translations[$errorCode])) {
            return $translations[$errorCode];
        }
        
        
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
        
        
        return $errorMessage ?: 'Kart bilgileri hatalı. Lütfen bilgilerinizi kontrol ediniz.';
    }

    /* public function createSubMerchantForStore($store): array
    {
        try{
        $request = new CreateSubMerchantRequest();
        $request->setLocale(Locale::TR);
        $request->setConversationId((string) $store->id . '_' . time());
        $request->setSubMerchantExternalId((string) $store->id);
        $request->setSubMerchantType("PRIVATE_COMPANY");

        $request->setAddress($store->address ?? "Adres Belirtilmedi");
        $request->setTaxOffice($store->tax_office ?? "Kadıköy");
        $request->setTaxNumber($store->tax_number ?? "1111111111"); 
        $companyTitle = $store->name ?: "Mağaza_" . $store->id;
        if (strlen($companyTitle) < 3) {
            $companyTitle = "Mağaza_" . $store->id;
        }
        $request->setLegalCompanyTitle($companyTitle);
        $request->setEmail($store->email ?? "test@test.com");
        $request->setGsmNumber($store->phone ?? "+905350000000");
        $request->setName($store->seller_name ?? "İyzico Test Satıcı");
        $request->setIban($store->iban ?? "TR180006200119000006672315");

        if ($store->identity_number) {
            $request->setIdentityNumber($store->identity_number);
        }

        $subMerchant = SubMerchant::create($request, $this->options);

        if ($subMerchant->getStatus() === 'success') {
            $store->update([
                'sub_merchant_key' => $subMerchant->getSubMerchantKey()
            ]);
            return [
                'success' => true,
                'sub_merchant_key' => $subMerchant->getSubMerchantKey(),
                'message' => 'Alt üye iş yeri başarıyla oluşturuldu'
            ];
        } 
        return [
            'success' => false,
            'error' => $subMerchant->getErrorMessage(),
            'error_code' => $subMerchant->getErrorCode()
        ];
        }catch(\Exception $e){
            return [
                'success' => false,
                'error' => 'Alt üye iş yeri oluşturulurken hata oluştu: ' . $e->getMessage()
            ];
        }

    }*/

    /*public function cancelPayment(string $paymentId): array
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
    }*/
}
