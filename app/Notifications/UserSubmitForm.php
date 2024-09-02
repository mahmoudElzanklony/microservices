<?php

namespace App\Notifications;

use App\Events\MyWebSocketEvent;
use App\Models\services;
use App\Services\SendEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserSubmitForm extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private $data){}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if(env('APP_MAIL')) {
            return ['mail', 'database'];
        }
        return ['database'];
    }

    public function toDatabase(object $notifiable)
    {
        $service = $this->data->service;
        $message = [
            'message'=>__('messages.submit_new_request').$service->name,
            'user_id'=>$service->user_id,
        ];
        event(new MyWebSocketEvent(json_encode($message,JSON_UNESCAPED_UNICODE)));
        return [
            'data' => json_encode(
                [
                    'ar' => 'تم حفظ بيانات جديده في خدمة  '.$service->name,
                    'en' =>  'New data has been saved successfully at service called '.$service->name
                ], JSON_UNESCAPED_UNICODE),
            'sender' => null
        ];
    }
    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $service = $this->data->service;
        SendEmail::send('بيانات جديدة تم حفظها في خدمة خاصه ب '.env('APP_NAME'),'تم حفظ بيانات جديده في خدمة  '.$service->name,env('APP_URL').'/notifications','press here',$service->user->email);
        return (new MailMessage)
            ->subject('Service cancelled successfully at '.env('APP_NAME'))
            ->view( 'emails.email', ['details' => ['title'=>'Service cancelled successfully at '.env('APP_NAME'),
                'body'=>'You cancel service successfully and its id is '.$this->order->id.' from order that id is'.$this->order->order->id,'link'=>env('APP_URL').'/notifications','link_msg'=>'press here']]);


    }

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
