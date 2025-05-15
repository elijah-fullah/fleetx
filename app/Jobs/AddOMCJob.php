<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use App\Mail\AddOMC;

class AddOMCJob implements ShouldQueue
{
    use Queueable;
    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $mail = new AddOMC($this->data);
        Mail::to(env('MAIL_TO_ADDRESS'))->send($mail);
    }
}
