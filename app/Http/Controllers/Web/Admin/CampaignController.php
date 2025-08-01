<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;

class CampaignController extends Controller
{
    public function campaign()
    {
        return view('Admin.Campaign.campaign');
    }
    public function storeCampaign()
    {
        return view('Admin.Campaign.storeCampaign');
    }
}