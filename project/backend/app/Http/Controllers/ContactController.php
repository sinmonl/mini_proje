<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function getCities()
    {
        try {

            $response = Http::get('https://turkiyeapi.dev/api/v1/provinces');

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['status']) && $data['status'] === 'OK' && isset($data['data'])) {
                    return response()->json([
                        'success' => true,
                        'data' => $data['data']
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'İller yüklenirken hata oluştu'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'API bağlantı hatası: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDistricts($cityName)
    {
        try {
            $cityResponse = Http::get('https://turkiyeapi.dev/api/v1/provinces');

            if (!$cityResponse->successful()) {
                throw new \Exception('İl bilgisi alınamadı');
            }

            $responseData = $cityResponse->json();
            if (!isset($responseData['status']) || $responseData['status'] !== 'OK' || !isset($responseData['data'])) {
                throw new \Exception('İl verisi formatı hatalı');
            }

            $cities = $responseData['data'];

            $decodedCityName = urldecode($cityName);
            $city = collect($cities)->first(function ($city) use ($decodedCityName) {
                return strtolower($city['name']) === strtolower($decodedCityName);
            });

            if (!$city) {
                return response()->json([
                    'success' => false,
                    'message' => 'İl bulunamadı: ' . $decodedCityName
                ], 404);
            }

            if (isset($city['districts']) && !empty($city['districts'])) {
                return response()->json([
                    'success' => true,
                    'data' => $city['districts']
                ]);
            }

            $districtsResponse = Http::get("https://turkiyeapi.dev/api/v1/provinces/{$city['id']}/districts");

            if ($districtsResponse->successful()) {
                $districtsData = $districtsResponse->json();

                if (isset($districtsData['status']) && $districtsData['status'] === 'OK' && isset($districtsData['data'])) {
                    return response()->json([
                        'success' => true,
                        'data' => $districtsData['data']
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'İlçeler yüklenirken hata oluştu'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'API bağlantı hatası: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'city' => 'required|string|max:255',
                'district' => 'required|string|max:255',
                'message' => 'required|string'
            ]);

            $contact = Contact::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Mesajınız başarıyla gönderildi',
                'data' => $contact
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation hatası',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}