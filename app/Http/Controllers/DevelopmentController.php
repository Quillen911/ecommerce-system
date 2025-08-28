<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DevelopmentController extends Controller
{
    public function showFakeSms()
    {
        $logFile = storage_path('logs/fake_sms.log');
        
        if (!File::exists($logFile)) {
            return view('development.fake-sms', [
                'smsLogs' => null,
                'smsCount' => 0
            ]);
        }
        
        $smsLogs = File::get($logFile);
        $smsCount = substr_count($smsLogs, "\n");
        
        return view('development.fake-sms', compact('smsLogs', 'smsCount'));
    }
    
    public function clearFakeSms()
    {
        $logFile = storage_path('logs/fake_sms.log');
        
        if (File::exists($logFile)) {
            File::delete($logFile);
        }
        
        return redirect()->route('development.fake-sms')
            ->with('success', 'Fake SMS logları temizlendi.');
    }
}
