<?php

namespace App\Classes;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class FTP
{
    public $directory = 'categories/';

    public function getFile()
    {
        try {
            $files = Storage::disk('ftp')->files($this->directory);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        $lastFileName = $this->getLastFile($files);
        if ($lastFileName == false) {
            return response()->json([
                'status' => 'error',
                'message' => 'No file found under directory.'
            ]);
        }

        // Download the file and save to public folder.
        $getLastFile = Storage::disk('ftp')->get('categories/' . $lastFileName);
        Storage::disk('uploads')->put($lastFileName, $getLastFile);

        return response()->json([
            'status' => 'success',
            'file' => $lastFileName
        ]);
    }

    public function getLastFile($files)
    {
        $dates = [];
        foreach ($files as $file) {
            $path_parts = pathinfo($file);
            // Extension control
            if (isset($path_parts['extension']) && $path_parts['extension'] == 'xlsx') {
                $date = explode('-', $path_parts['filename']);
                $date = end($date);
                // Invalid date check
                if ($this->isDateValid($date)) {
                    $convertToDate = Carbon::createFromFormat('YmdHis', $date)->toDateTimeString();
                    $dates[] = [
                        'file_name' => $path_parts['basename'],
                        'date' => $convertToDate
                    ];
                }
            }
        }

        if (count($dates) == 0) {
            return false;
        }

        return max($dates)['file_name'];
    }

    public function isDateValid($date)
    {
        try {
            Carbon::createFromFormat('YmdHis', $date)->toDateTimeString();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

}
