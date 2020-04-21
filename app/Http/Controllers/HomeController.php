<?php

namespace App\Http\Controllers;

use App\Classes\FTP;
use App\Jobs\addCategoryToDB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{

    /**
     * @return JsonResponse
     */
    public function index()
    {
        $file = $this->storageProcess();

        try {
            // Job needed to export data from excel file to database dispatched.
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


    /**
     * Download the latest excel file and return it.
     *
     * @return JsonResponse
     */
    public function storageProcess()
    {
        $ftp = new FTP();
        try {
            $file = Storage::disk('ftp')->get('categories/' . $ftp->getFile());
            Storage::disk('uploads')->put($ftp->getFile(), $file);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        return $ftp->getFile();
    }
}
