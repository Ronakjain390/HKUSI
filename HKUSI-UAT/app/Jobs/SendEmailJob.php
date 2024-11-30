<?php

namespace App\Jobs;
 
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\ForgotPasswordMail;
use App\Mail\AccountActivate;
use App\Mail\HallReservation;
use App\Mail\RegisterTemplate;
use App\Mail\HallReservationConfirm;
use App\Mail\UpdatePassword;
use App\Mail\HallInfoUpdate;
use App\Mail\PaymentSuccessfull;
use App\Mail\EventPaymentSuccessfull;
use App\Mail\FullyBookedMale;
use App\Mail\PaymentDeadlineMail;
use App\Mail\InformationReleased;
use App\Mail\SpecialConfermation;
use App\Mail\EventReminder;
use App\Mail\EventCancelled;
use App\Mail\EventInformationUpdate;
use App\Mail\PrivateEventCancelled;
use App\Mail\CheckOut;
use Mail, Config,Env;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        if (isset($this->details['type']) && $this->details['type'] == 'ResetPasswordTemplate'){
            $email = new ForgotPasswordMail($this->details['mailInfo']);
            Mail::to($this->details['email'])->send($email);
        }elseif(isset($this->details['type']) && $this->details['type'] == 'RegisterTemplate'){
            $email = new RegisterTemplate($this->details['mailInfo']);
            Mail::to($this->details['email'])->send($email);
        }elseif(isset($this->details['type']) && $this->details['type'] == 'AccountActivation'){
            $email = new AccountActivate($this->details['mailInfo']);
            Mail::to($this->details['email'])->send($email);
        }elseif(isset($this->details['type']) && $this->details['type'] == 'HallReservation'){
            $email = new HallReservation($this->details['mailInfo']);
            Mail::to($this->details['email'])->send($email);
        }elseif(isset($this->details['type']) && $this->details['type'] == 'HallReservationConfirm'){
            $email = new HallReservationConfirm($this->details['mailInfo']);
            Mail::to($this->details['email'])->send($email);
        }elseif(isset($this->details['type']) && $this->details['type'] == 'UpdatePassword'){
            $email = new UpdatePassword($this->details['mailInfo']);
            Mail::to($this->details['email'])->send($email);
        }elseif(isset($this->details['type']) && $this->details['type'] == 'HallInfoUpdate'){
            $email = new HallInfoUpdate($this->details['mailInfo']);
            Mail::to($this->details['email'])->send($email);
        }elseif(isset($this->details['type']) && $this->details['type'] == 'PaymentSuccessfull'){
            $email = new PaymentSuccessfull($this->details['mailInfo']);
            Mail::to($this->details['email'])->send($email);
        }elseif(isset($this->details['type']) && $this->details['type'] == 'FullyBookedMale'){
            $email = new FullyBookedMale($this->details['mailInfo']);
            Mail::to($this->details['email'])->send($email);
        }elseif(isset($this->details['type']) && $this->details['type'] == 'InformationReleased'){
            $email = new InformationReleased($this->details['mailInfo']);
            Mail::to($this->details['email'])->send($email);
        }elseif(isset($this->details['type']) && $this->details['type'] == 'PaymentDeadlineMail'){
            $email = new PaymentDeadlineMail($this->details['mailInfo']);
            Mail::to($this->details['email'])->send($email);
        }elseif(isset($this->details['type']) && $this->details['type'] == 'SpecialConfermation'){
            $email = new SpecialConfermation($this->details['mailInfo']);
            Mail::to($this->details['email'])->send($email);
        }elseif(isset($this->details['type']) && $this->details['type'] == 'EventPaymentSuccessfull'){
            $email = new EventPaymentSuccessfull($this->details['mailInfo']);
            Mail::to($this->details['email'])->send($email);
        }elseif(isset($this->details['type']) && $this->details['type'] == 'EventReminder'){
            $email = new EventReminder($this->details['mailInfo']);
            Mail::to($this->details['email'])->send($email);
        }elseif(isset($this->details['type']) && $this->details['type'] == 'EventCancelled'){
            $email = new EventCancelled($this->details['mailInfo']);
            Mail::to($this->details['email'])->send($email);
        }elseif(isset($this->details['type']) && $this->details['type'] == 'PrivateEventCancelled'){
            // Private Event Cancelled mail By Akash
            $email = new PrivateEventCancelled($this->details['mailInfo']);
            Mail::to($this->details['email'])->send($email);
        }elseif(isset($this->details['type']) && $this->details['type'] == 'EventInformationUpdate'){
            dd(env('MAIL_USERNAME'));
            $email = new EventInformationUpdate($this->details['mailInfo']);
            Mail::to($this->details['email'])->send($email);
        }elseif(isset($this->details['type']) && $this->details['type'] == 'HallCheckOut'){
            $email = new CheckOut($this->details['mailInfo']);
            Mail::to($this->details['email'])->send($email);
        }
    }
}
