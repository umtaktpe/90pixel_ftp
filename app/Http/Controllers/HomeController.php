<?php

namespace App\Http\Controllers;

use App\Classes\FTP;
use App\Jobs\addCategoryToDB;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index(FTP $ftp)
    {
        try {
            $file = Storage::disk('ftp')->get($ftp->getFile());
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
