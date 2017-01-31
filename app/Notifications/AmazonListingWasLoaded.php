<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AmazonListingWasLoaded extends Notification
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $stats = $this->user->planStats();

        $plan = strtoupper($stats->plan);
        $listingCount = $this->user->amazonMerchantListing->count();

        $nProducts = $stats->allocation == 1 ? '1 product' : "$stats->allocation products";

        $message = <<<MSG
Your Amazon listings are now loaded to SKU Bright.  Your plan: $plan has a total monitoring allocation for $nProducts.
MSG;

        return (new MailMessage)
            ->subject("[SKUBright] Your Amazon Listings are loaded ($listingCount products)")
            ->greeting('Hello ' . $this->user->name . '!')
            ->line($message)
            ->action('See your Amazon listings', url("/my/dashboard"))
            ->line('Thank you for using our service!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
