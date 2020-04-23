<?php

namespace App\Jobs;

use App\Classes\CategoryDB;
use App\Mail\CategoryAdded;
use Illuminate\Bus\Queueable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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
     */
    public function handle()
    {
        $categoryDB = new CategoryDB($this->file);

        $categoryDB->addToDB();

        Mail::to('buradayim@90pixel.com')
            ->queue(new CategoryAdded());
    }
}
