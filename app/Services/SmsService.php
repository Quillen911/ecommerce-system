<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class SmsService
{
    public function send($phoneNumber, $message)
    {
        if (app()->environment('local', 'development')) {
            Log::info("SMS Gönderildi:", [
                'phone' => $phoneNumber,
                'message' => $message,
                'timestamp' => now()
            ]);
            
            // Storage'a kaydet (opsiyonel)
            $this->saveFakeSms($phoneNumber, $message);
            
            return true;
        }
        
        // Production'da gerçek SMS (opsiyonel)
        return $this->sendRealSms($phoneNumber, $message);
    }

    private function saveFakeSms($phoneNumber, $message)
    {
        $logFile = storage_path('logs/fake_sms.log');
        $logEntry = date('Y-m-d H:i:s') . " | {$phoneNumber} | {$message}\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }
    
    private function sendRealSms($phoneNumber, $message)
    {
        // İleride gerçek SMS sağlayıcısı eklemek için
        Log::info("[REAL SMS] Gönderildi: {$phoneNumber} - {$message}");
        return true;
    }
}