<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\CourierService;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $activeCourier = CourierService::getActiveCourier();
        return view('admin.couriers.index', compact('settings', 'activeCourier'));
    }

    public function update(Request $request)
    {
        $fields = [
            'active_courier',
            // Steadfast
            'steadfast_api_key',
            'steadfast_secret_key',
            'steadfast_base_url',
            // Pathao
            'pathao_client_id',
            'pathao_client_secret',
            'pathao_username',
            'pathao_password',
            'pathao_store_id',
            'pathao_base_url',
            // RedX
            'redx_api_token',
            'redx_base_url',
            // BD Courier / Fraud Checker
            'bdcourier_api_key',
            'bdcourier_api_url',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                Setting::set($field, $request->input($field));
            }
        }

        return back()->with('success', 'Courier and Fraud Checker API configurations updated successfully!');
    }
}

