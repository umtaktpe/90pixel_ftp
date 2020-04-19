<?php

namespace App\Jobs;

use App\Classes\CategoryDB;
use App\Mail\CategoryAdded;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class addCategoryToDB implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;

    /**
     * Create a new job instance.
     *
     * @param $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }


    /**
     * Execute the job.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle()
    {
        $categoryDB = new CategoryDB($this->file);

        $response = $categoryDB->addToDB();
        $response = json_decode($response->getContent())->status;

        if ($response == 'error') {
            return response()->json([
                'status' => 'error',
                'message' => 'Somethings wrong!'
            ]);
        }

        Mail::to('example@gmail.com')
            ->queue(new CategoryAdded());

        return response()->json([
            'status' => 'success',
            'message' => 'Process completed!'
        ]);
    }
}
