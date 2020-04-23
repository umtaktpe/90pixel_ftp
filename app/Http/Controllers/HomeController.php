<?php

namespace App\Http\Controllers;

use App\Classes\FTP;
use App\Jobs\addCategoryToDB;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{

    /**
     * @return JsonResponse
     */
    public function index()
    {
        $ftp = new FTP();
        $response = $ftp->getFile()->getData();

        if ($response->status == 'error') {
            return response()->json($response);
        }

        try {
            // Job needed to export data from excel file to database dispatched.
            $response = addCategoryToDB::dispatch($response->file);
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
