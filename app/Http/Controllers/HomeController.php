<?php

namespace App\Http\Controllers;

use App\Category;
use App\Classes\FTP;
use App\Jobs\addCategoryToDB;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index(FTP $ftp)
    {
        $file = [
            [
                'category_name' => 'Eğitim - Gelişim',
                'children' => [
                    [
                        'category_name' => 'Dil',
                        'children' => [
                            ['category_name' => 'İşaret Dili'],
                            ['category_name' => 'İngilizce (Online)'],
                            ['category_name' => 'İngilizce (Sınıf ve Özel Ders)'],
                            ['category_name' => 'Diğer Diller'],
                        ],
                    ],
                    [
                        'category_name' => 'Konuşmacı',
                    ],
                    [
                        'category_name' => 'Kişisel Gelişim',
                        'children' => [
                            ['category_name' => 'İletişim - İlişki Yönetimi'],
                            ['category_name' => 'Stres Yönetimi - Mindfulness'],
                            ['category_name' => 'Sunum Tasarımı - Yapma'],
                            ['category_name' => 'Kişisel Üretkenlik'],
                        ],
                    ],
                ],
            ],
            [
                'category_name' => 'Teknoloji',
                'children' => [
                    [
                        'category_name' => 'ERP',
                    ],
                    [
                        'category_name' => 'VR & AR',
                    ],
                    [
                        'category_name' => 'Chatbot',
                    ],
                ],
            ],
        ];

        try {
            //$file = Storage::disk('ftp')->get($ftp->getFile());
            addCategoryToDB::dispatch($file);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data added to database successfully.'
        ]);
    }
}
