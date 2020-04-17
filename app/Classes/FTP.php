<?php
namespace App\Classes;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class FTP {
    public $directory = 'categories/';

    public function getFile()
    {
        try {
            $files = Storage::disk('ftp')->files($this->directory);
            return $this->getLastFile($files);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getLastFile($files)
    {
        $dates = [];
        foreach ($files as $file) {
            $path_parts = pathinfo($file);
            if (isset($path_parts['extension']) == 'xlsx') {
                $date = explode('-', $path_parts['filename']);
                $date = end($date);
                if ($this->isDateValid($date)) {
                    $convertToDate = Carbon::createFromFormat('YmdHis', $date)->toDateTimeString();
                    array_push($dates, $convertToDate);
                }
            }
        }

        return max($dates);
    }

    protected function isDateValid($date)
    {
        try {
            Carbon::createFromFormat('YmdHis', $date)->toDateTimeString();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
