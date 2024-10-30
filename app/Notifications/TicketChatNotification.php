<?php

namespace App\Notifications;

use App\Events\MyWebSocketEvent;
use App\Services\SendEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketChatNotification extends Notification
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
            'message'=>__('messages.submit_new_request_ticket_chat').$this->data->service->name,
            'user_id'=>$this->data->type == 'client' ? $this->data->service->user_id:$this->data->owner->id,
        ];
        event(new MyWebSocketEvent(json_encode($message,JSON_UNESCAPED_UNICODE)));
        return [
            'data' => json_encode(
                [
                    'ar' => 'تم حفظ رد جديد علي خدمه  '.$this->data->service->name,
                    'en' =>  'New reply comming from '.$this->data->service->name
                ], JSON_UNESCAPED_UNICODE),
            'sender' => auth()->id()
        ];
    }
    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $service = $this->data->service;
        SendEmail::send('تم حفظ رد جديد علي موقع  '.env('APP_NAME').'من خدمة '.$this->data->service->name,
            'هناك رد جديد لم تقرأه بعد علي خدمة '.$this->data->service->name,
            env('APP_URL').'/clients/chat?id='.$this->data->id.'&service_id='.$this->data->service->id,
            'press here',$service->user->email);
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
