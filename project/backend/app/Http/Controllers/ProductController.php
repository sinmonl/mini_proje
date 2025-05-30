<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = [
                [
                    'id' => 1,
                    'name' => 'Ürün 1',
                    'description' => 'Ürün 1 açıklaması burada yer alacak.',
                    'price' => 199.99,
                    'image' => asset('images/products/gorsel-2.jpg')
                ],
                [
                    'id' => 2,
                    'name' => 'Ürün 2',
                    'description' => 'Ürün 2 açıklaması burada yer alacak.',
                    'price' => 299.99,
                    'image' => asset('images/products/gorsel-3.jpg')
                ],
                [
                    'id' => 3,
                    'name' => 'Ürün 3',
                    'description' => 'Ürün 3 açıklaması burada yer alacak.',
                    'price' => 399.99,
                    'image' => asset('images/products/gorsel-4.jpg')
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $products
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ürünler yüklenirken bir hata oluştu'
            ], 500);
        }
    }


}