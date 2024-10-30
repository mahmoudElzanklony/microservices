<?php

namespace App\Notifications;

use App\Models\User;
use App\Services\SendEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRegisteryNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $user;
    private $is_client = false;
    private $send_email = false;
    public function __construct($user,$is_client = false,$send_email = false)
    {
        //
        $this->user = $user;
        $this->is_client = $is_client;
        $this->send_email = $send_email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if($this->send_email){
            return ['database','mail'];
        }
        return ['database'];
    }


    public function toDatabase(object $notifiable)
    {
        if($this->is_client){
            return [
                'data' => json_encode(['ar' => 'تمت عملية التسجيل بنجاح في '.env('APP_NAME'),
                    'en' =>  'Register process done successfully at '.env('APP_NAME')], JSON_UNESCAPED_UNICODE),
                'sender' => $this->user->id
            ];
        }else {
            return [
                'data' => json_encode(['ar' => $this->user->username . ' قام بالتسجيل بنجاح الي المنصة ', 'en' => $this->user->username . ' registered in our app'], JSON_UNESCAPED_UNICODE),
                'sender' => $this->user->id
            ];
        }
    }

    public function toMail(object $notifiable)
    {
        SendEmail::send('تمت عملية التسجيل الخاصه بك في ' . env('APP_NAME').' بنجاح ', 'تم انشاء عضويه لك بنجاح في منصه ' . env('APP_NAME').' في حالة نسيان كلمة المرور و انشاء واحده جديدة يمكنك الضغط هنا علي هذا الرابط ', env('DESIGN_URL').'/auth/new-password?otp='.$this->user->otp_secret, 'press here', $this->user->email);
        return (new MailMessage)
            ->subject('Register process done successfully at ' . env('APP_NAME'))
            ->view( 'emails.email', ['details' => ['title'=>'Register process done successfully at ' . env('APP_NAME'),
                'body'=>' Register process done successfully if you want to change your password and make new one you can press on below link ','link'=>env('DESIGN_URL').'/auth/new-password?otp='.$this->user->otp_secret,'link_msg'=>'press here']]);


    }
    /**
     * Get the mail representation of the notification.
     */

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
