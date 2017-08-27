<?php
namespace App\Services;

use App\Services\Interfaces\IMessageService;
use App\Message;
use Carbon\Carbon;

class MessageService implements IMessageService
{
    public function getMessages($id) {
        return Message::where(['UserID' => $id])->get();
    }

    public function createMessage($id, $isUser, $data) {
        $message = new Message();
        $message->Message = $data['Message'];
        $message->Image = $data['Image'];
        $message->UserID = $id;
        $message->IsUser = $isUser;
        $message->Date = Carbon::now('Europe/Athens');
        $message->save();
    }
}