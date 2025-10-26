<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\SmsService;

class SmsChannel
{
    protected $smsService;
    
    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }
    
    public function send($notifiable, Notification $notification)
    {
        if (method_exists($notification, 'toSms')) {
            $message = $notification->toSms($notifiable);
            $phoneNumber = $notifiable->phone ?? 5555555555;
             
            return $this->smsService->send($phoneNumber, $message);
        }
    }
}