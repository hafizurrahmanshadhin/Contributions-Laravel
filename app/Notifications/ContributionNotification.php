<?php

namespace App\Notifications;

use App\Models\Collection;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ContributionNotification extends Notification {
    use Queueable;

    protected $contributor;
    protected $collection;
    protected $amount;

    /**
     * Create a new notification instance.
     *
     * @param mixed $contributor
     * @param Collection $collection
     * @param float $amount
     */
    public function __construct($contributor, Collection $collection, $amount) {
        $this->contributor = $contributor;
        $this->collection  = $collection;
        $this->amount      = $amount;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array {
        $message = sprintf(
            "%s donated your collection $%s",
            $this->contributor->name,
            $this->amount
        );

        return [
            'message'           => $message,
            'user_id'           => $this->contributor->id ?? null,
            'user_name'         => $this->contributor->name,
            'collection_id'     => $this->collection->id,
            'collection_title'  => $this->collection->name,
            'contribution_time' => now()->toDateTimeString(),
        ];
    }
}
