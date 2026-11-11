<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CourierService
{
    /**
     * Get the currently active courier key
     */
    public static function getActiveCourier(): string
    {
        return Setting::get('active_courier', 'steadfast');
    }

    /**
     * Get active courier human label
     */
    public static function getActiveCourierName(): string
    {
        $active = self::getActiveCourier();
        return match ($active) {
            'steadfast' => 'Steadfast Courier',
            'pathao'    => 'Pathao Courier',
            'redx'      => 'RedX Courier',
            default     => 'Steadfast Courier',
        };
    }

    /**
     * Dispatch order to active courier
     */
    public function dispatchOrder(Order $order, array $data = []): array
    {
        $courier = $data['courier_name'] ?? self::getActiveCourier();

        return match ($courier) {
            'steadfast' => $this->sendToSteadfast($order, $data),
            'pathao'    => $this->sendToPathao($order, $data),
            'redx'      => $this->sendToRedx($order, $data),
            default     => $this->sendToSteadfast($order, $data),
        };
    }

    /**
     * Steadfast Courier API Integration
     */
    public function sendToSteadfast(Order $order, array $data): array
    {
        $apiKey = Setting::get('steadfast_api_key');
        $secretKey = Setting::get('steadfast_secret_key');
        $baseUrl = rtrim(Setting::get('steadfast_base_url', 'https://portal.steadfast.com.bd/api/v1'), '/');

        if (empty($apiKey) || empty($secretKey)) {
            return [
                'success' => false,
                'message' => 'Steadfast API Key or Secret Key is not configured in Admin Settings > Couriers.',
            ];
        }

        $recipientPhone = $data['customer_phone'] ?? $order->customer_phone;
        // Clean phone number (strip whitespace, hyphens)
        $cleanPhone = preg_replace('/[^0-9]/', '', $recipientPhone);
        if (str_starts_with($cleanPhone, '880')) {
            $cleanPhone = '0' . substr($cleanPhone, 3);
        }

        $payload = [
            'invoice'           => (string)($order->order_number),
            'recipient_name'    => (string)($data['customer_name'] ?? $order->customer_name),
            'recipient_phone'   => (string)$cleanPhone,
            'recipient_address' => (string)($data['customer_address'] ?? $order->customer_address),
            'cod_amount'        => (float)($data['cod_amount'] ?? $order->total),
            'note'              => (string)($data['note'] ?? $order->customer_note ?? 'Handle with care'),
        ];

        try {
            $response = Http::withHeaders([
                'Api-Key'      => $apiKey,
                'Secret-Key'   => $secretKey,
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ])->timeout(20)->post("{$baseUrl}/create_order", $payload);

            $resData = $response->json();

            if ($response->successful() && isset($resData['status']) && $resData['status'] == 200) {
                $consignment = $resData['consignment'] ?? [];
                $consignmentId = $consignment['consignment_id'] ?? $consignment['tracking_code'] ?? null;
                $trackingCode = $consignment['tracking_code'] ?? $consignmentId;

                $order->update([
                    'courier_name'     => 'steadfast',
                    'consignment_id'   => $consignmentId,
                    'tracking_code'    => $trackingCode,
                    'courier_status'   => $consignment['status'] ?? 'in_review',
                    'courier_response' => json_encode($resData),
                    'status'           => 'shipped',
                    'shipped_at'       => now(),
                ]);

                return [
                    'success'        => true,
                    'message'        => 'Order successfully created in Steadfast Courier!',
                    'consignment_id' => $consignmentId,
                    'tracking_code'  => $trackingCode,
                    'data'           => $resData,
                ];
            }

            $errorMessage = $resData['message'] ?? $response->body() ?? 'Failed to create order in Steadfast';
            if (isset($resData['errors'])) {
                $errorMessage .= ' (' . json_encode($resData['errors']) . ')';
            }

            return [
                'success' => false,
                'message' => $errorMessage,
            ];
        } catch (\Exception $e) {
            Log::error('Steadfast Courier API Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Steadfast Connection Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Pathao Courier API Integration
     */
    public function sendToPathao(Order $order, array $data): array
    {
        $clientId = Setting::get('pathao_client_id');
        $clientSecret = Setting::get('pathao_client_secret');
        $username = Setting::get('pathao_username');
        $password = Setting::get('pathao_password');
        $storeId = Setting::get('pathao_store_id');
        $baseUrl = rtrim(Setting::get('pathao_base_url', 'https://courier-api-bi.pathao.com'), '/');

        if (empty($clientId) || empty($clientSecret) || empty($username) || empty($password)) {
            return [
                'success' => false,
                'message' => 'Pathao credentials (Client ID, Secret, Username, Password) are missing in Settings.',
            ];
        }

        try {
            // 1. Issue Access Token
            $tokenRes = Http::asJson()->timeout(15)->post("{$baseUrl}/aladdin/api/v1/issue-token", [
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'username'      => $username,
                'password'      => $password,
                'grant_type'    => 'password',
            ]);

            if (!$tokenRes->successful() || empty($tokenRes['access_token'])) {
                return [
                    'success' => false,
                    'message' => 'Pathao Authentication Failed: ' . ($tokenRes['message'] ?? $tokenRes->body()),
                ];
            }

            $token = $tokenRes['access_token'];

            // Clean phone
            $cleanPhone = preg_replace('/[^0-9]/', '', $data['customer_phone'] ?? $order->customer_phone);

            // 2. Create Order in Pathao
            $orderPayload = [
                'store_id'            => (int)$storeId,
                'merchant_order_id'   => (string)$order->order_number,
                'recipient_name'      => (string)($data['customer_name'] ?? $order->customer_name),
                'recipient_phone'     => (string)$cleanPhone,
                'recipient_address'   => (string)($data['customer_address'] ?? $order->customer_address),
                'recipient_city'      => 1, // Default Dhaka
                'recipient_zone'      => 1,
                'delivery_type'       => 48, // 48hr delivery
                'item_type'           => 2, // Parcel
                'special_instruction' => (string)($data['note'] ?? $order->customer_note ?? 'Handle with care'),
                'item_quantity'       => max(1, $order->items->sum('quantity')),
                'item_weight'         => 0.5,
                'amount_to_collect'   => (float)($data['cod_amount'] ?? $order->total),
                'item_description'    => 'Saree / Boutique Clothing',
            ];

            $createRes = Http::withToken($token)
                ->asJson()
                ->timeout(20)
                ->post("{$baseUrl}/aladdin/api/v1/orders", $orderPayload);

            $resData = $createRes->json();

            if ($createRes->successful() && isset($resData['data']['consignment_id'])) {
                $consignmentId = $resData['data']['consignment_id'];
                $order->update([
                    'courier_name'     => 'pathao',
                    'consignment_id'   => $consignmentId,
                    'tracking_code'    => $consignmentId,
                    'courier_status'   => 'created',
                    'courier_response' => json_encode($resData),
                    'status'           => 'shipped',
                    'shipped_at'       => now(),
                ]);

                return [
                    'success'        => true,
                    'message'        => 'Order successfully created in Pathao Courier!',
                    'consignment_id' => $consignmentId,
                    'tracking_code'  => $consignmentId,
                ];
            }

            return [
                'success' => false,
                'message' => 'Pathao Error: ' . ($resData['message'] ?? $createRes->body()),
            ];
        } catch (\Exception $e) {
            Log::error('Pathao API Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Pathao Connection Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * RedX Courier API Integration
     */
    public function sendToRedx(Order $order, array $data): array
    {
        $token = Setting::get('redx_api_token');
        $baseUrl = rtrim(Setting::get('redx_base_url', 'https://openapi.redx.com.bd/v1.0.0-beta'), '/');

        if (empty($token)) {
            return [
                'success' => false,
                'message' => 'RedX API Access Token is missing in Settings.',
            ];
        }

        try {
            $cleanPhone = preg_replace('/[^0-9]/', '', $data['customer_phone'] ?? $order->customer_phone);

            $payload = [
                'customer_name'          => (string)($data['customer_name'] ?? $order->customer_name),
                'customer_phone'         => (string)$cleanPhone,
                'delivery_area'          => 'Dhaka',
                'delivery_area_id'       => 1,
                'customer_address'       => (string)($data['customer_address'] ?? $order->customer_address),
                'merchant_invoice_id'    => (string)$order->order_number,
                'cash_collection_amount' => (float)($data['cod_amount'] ?? $order->total),
                'parcel_weight'          => 500,
                'instruction'            => (string)($data['note'] ?? $order->customer_note ?? 'Handle with care'),
                'value'                  => (float)($data['cod_amount'] ?? $order->total),
            ];

            $response = Http::withHeaders([
                'API-ACCESS-TOKEN' => 'Bearer ' . trim($token),
                'Content-Type'     => 'application/json',
            ])->timeout(20)->post("{$baseUrl}/parcels", $payload);

            $resData = $response->json();

            if ($response->successful() && !empty($resData['tracking_id'])) {
                $trackingId = $resData['tracking_id'];
                $order->update([
                    'courier_name'     => 'redx',
                    'consignment_id'   => $trackingId,
                    'tracking_code'    => $trackingId,
                    'courier_status'   => 'created',
                    'courier_response' => json_encode($resData),
                    'status'           => 'shipped',
                    'shipped_at'       => now(),
                ]);

                return [
                    'success'        => true,
                    'message'        => 'Order successfully created in RedX Courier!',
                    'consignment_id' => $trackingId,
                    'tracking_code'  => $trackingId,
                ];
            }

            return [
                'success' => false,
                'message' => 'RedX Error: ' . ($resData['message'] ?? $response->body()),
            ];
        } catch (\Exception $e) {
            Log::error('RedX API Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'RedX Connection Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * BD Courier Fraud Checker (https://app.courier.com.bd/api)
     * Queries BD Courier multi-courier API (Pathao, Steadfast, RedX, Paperfly, etc.)
     * using the API Key configured by the merchant.
     */
    public function checkFraud(string $phone): array
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($cleanPhone, '880')) {
            $cleanPhone = '0' . substr($cleanPhone, 3);
        }

        $bdCourierKey = trim(Setting::get('bdcourier_api_key', ''));

        // If BD Courier API key is not configured, inform admin with link
        if (empty($bdCourierKey)) {
            return [
                'success'       => false,
                'is_configured' => false,
                'phone'         => $cleanPhone,
                'message'       => 'BD Courier API Key সেট করা হয়নি। দয়া করে Settings > Couriers & APIs পেজে গিয়ে https://app.courier.com.bd/api থেকে API Key বসান।',
            ];
        }

        // Potential endpoints for BD Courier
        $customUrl = trim(Setting::get('bdcourier_api_url', ''));
        $endpoints = array_unique(array_filter([
            $customUrl,
            'https://api.bdcourier.com/courier-check',
            'https://courier.com.bd/api/courier-check',
        ]));

        $lastError = 'BD Courier API এ সংযোগ করা সম্ভব হয়নি।';

        foreach ($endpoints as $endpoint) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $bdCourierKey,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ])->timeout(12)->post($endpoint, [
                    'phone'   => $cleanPhone,
                    'api_key' => $bdCourierKey,
                ]);

                $json = $response->json();

                if ($response->status() === 401 || (isset($json['status']) && $json['status'] === 'error')) {
                    return [
                        'success'       => false,
                        'is_configured' => true,
                        'phone'         => $cleanPhone,
                        'message'       => 'BD Courier API Error: ' . ($json['message'] ?? 'Invalid API key or unauthorized request.'),
                    ];
                }

                if ($response->successful() && is_array($json)) {
                    $totalParcels = 0;
                    $successParcels = 0;
                    $cancelledParcels = 0;
                    $successRate = 100;
                    $courierBreakdown = [];

                    // Check if BD Courier provided official summary block first
                    $hasOfficialSummary = false;
                    if (isset($json['data']['summary']) && is_array($json['data']['summary'])) {
                        $summary = $json['data']['summary'];
                        $totalParcels = (int)($summary['total_parcel'] ?? ($summary['total_parcels'] ?? 0));
                        $successParcels = (int)($summary['success_parcel'] ?? ($summary['success_parcels'] ?? 0));
                        $cancelledParcels = (int)($summary['cancelled_parcel'] ?? ($summary['cancelled_parcels'] ?? 0));
                        $successRate = isset($summary['success_ratio']) 
                            ? round((float)$summary['success_ratio']) 
                            : ($totalParcels > 0 ? round(($successParcels / $totalParcels) * 100) : 100);
                        $hasOfficialSummary = true;
                    }

                    // Extract courier breakdown data (Pathao, Steadfast, RedX, etc.)
                    if (isset($json['data']) && is_array($json['data'])) {
                        foreach ($json['data'] as $courierSlug => $item) {
                            // CRITICAL: Skip 'summary' or non-courier array to prevent duplicate doubling of totals
                            if ($courierSlug === 'summary' || !is_array($item)) {
                                continue;
                            }

                            $tp = (int)($item['total_parcel'] ?? ($item['total_parcels'] ?? 0));
                            $sp = (int)($item['success_parcel'] ?? ($item['success_parcels'] ?? 0));
                            $cp = (int)($item['cancelled_parcel'] ?? ($item['cancelled_parcels'] ?? 0));

                            // Only accumulate if no official summary block was present
                            if (!$hasOfficialSummary) {
                                $totalParcels += $tp;
                                $successParcels += $sp;
                                $cancelledParcels += $cp;
                            }

                            // Include all couriers in the breakdown as returned by BD Courier
                            $courierBreakdown[] = [
                                'courier'          => $item['name'] ?? ucfirst($courierSlug),
                                'logo'             => $item['logo'] ?? null,
                                'total_parcel'     => $tp,
                                'success_parcel'   => $sp,
                                'cancelled_parcel' => $cp,
                                'success_ratio'    => isset($item['success_ratio']) ? (float)$item['success_ratio'] : ($tp > 0 ? round(($sp / $tp) * 100) : 0),
                            ];
                        }
                    }

                    // Fallback to top-level parcel counts if data array was empty
                    if ($totalParcels === 0 && !$hasOfficialSummary) {
                        $totalParcels = (int)($json['total_parcels'] ?? ($json['total_parcel'] ?? 0));
                        $successParcels = (int)($json['success_parcels'] ?? ($json['success_parcel'] ?? 0));
                        $cancelledParcels = (int)($json['cancelled_parcels'] ?? ($json['cancelled_parcel'] ?? 0));
                    }

                    if (!$hasOfficialSummary) {
                        $successRate = isset($json['success_ratio']) 
                            ? round((float)$json['success_ratio']) 
                            : ($totalParcels > 0 ? round(($successParcels / $totalParcels) * 100) : 100);
                    }

                    // Parse risk verdict cleanly without [object Object]
                    $riskVerdict = null;
                    $riskLevel = $successRate >= 80 ? 'Safe' : ($successRate >= 60 ? 'Medium' : 'High Risk');

                    if (isset($json['risk_verdict'])) {
                        if (is_array($json['risk_verdict'])) {
                            $action = $json['risk_verdict']['action'] ?? '';
                            $reasons = '';
                            if (isset($json['risk_verdict']['reasons']) && is_array($json['risk_verdict']['reasons'])) {
                                $reasons = implode(', ', $json['risk_verdict']['reasons']);
                            } elseif (isset($json['risk_verdict']['message'])) {
                                $reasons = $json['risk_verdict']['message'];
                            }
                            $riskVerdict = trim(($action ? "{$action}: " : "") . $reasons);
                            $riskLevel = $json['risk_verdict']['label'] ?? ($json['risk_verdict']['level'] ?? $riskLevel);
                        } elseif (is_string($json['risk_verdict'])) {
                            $riskVerdict = $json['risk_verdict'];
                        }
                    }

                    if (empty($riskVerdict)) {
                        $riskVerdict = $successRate >= 80 ? 'কাস্টমারের ডেলিভারি ট্র্যাক রেকর্ড ভালো।' : 'কাস্টমারের রিটার্ন রেট বেশি, সতর্ক থাকুন।';
                    }

                    return [
                        'success'           => true,
                        'provider'          => 'BD Courier (app.courier.com.bd)',
                        'phone'             => $cleanPhone,
                        'total_parcels'     => $totalParcels,
                        'success_parcels'   => $successParcels,
                        'cancelled_parcels' => $cancelledParcels,
                        'success_rate'      => $successRate,
                        'risk_level'        => ucfirst($riskLevel),
                        'risk_verdict'      => $riskVerdict,
                        'breakdown'         => $courierBreakdown,
                    ];
                }
            } catch (\Exception $e) {
                Log::warning("BD Courier API request to {$endpoint} failed: " . $e->getMessage());
                $lastError = $e->getMessage();
            }
        }

        return [
            'success'       => false,
            'is_configured' => true,
            'phone'         => $cleanPhone,
            'message'       => 'BD Courier API সংযোগ ব্যর্থ: ' . $lastError,
        ];
    }
}

