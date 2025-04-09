<?php



namespace App\Notifications;

use Illuminate\Notifications\Notification;

class GameApprovedNotification extends Notification
{
    private $message;
    private $gameId;

    public function __construct($message, $gameId = null)
    {
        $this->message = $message;
        $this->gameId = $gameId;
    }

    public function via($notifiable)
    {
        return ['database'];  
    }

    public function toDatabase($notifiable)
    {
        return [
            'from_user_id' => auth()->user()->id, 
            'to_user_id' => $notifiable->id,       
            'game_id' => $this->gameId,             
            'message' => $this->message,           
            'is_read' => false,                     
        ];
    }
}



