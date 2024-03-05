<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCode;
use App\Mail\ResetPasswordCode;

class SendVerificationOtp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $email;
    public $code;
    public $resetPassword;

    public function __construct($email, $code, $resetPassword)
    {
        $this->email = $email;
        $this->code = $code;
        $this->resetPassword = $resetPassword;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->resetPassword) {
            Mail::to($this->email)->send(new ResetPasswordCode($this->code));
        } else {
            Mail::to($this->email)->send(new VerificationCode($this->code));
        }
    }
}
