<?php

namespace App\Http\Controllers;

use App\Classes\FTP;
use App\Jobs\addCategoryToDB;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $file = $this->storageProcess();

        try {
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

    public function storageProcess()
    {
        $ftp = new FTP();
        $file = Storage::disk('ftp')->get('categories/' . $ftp->getFile());
        Storage::disk('uploads')->put($ftp->getFile(), $file);
        return $ftp->getFile();
    }
}
