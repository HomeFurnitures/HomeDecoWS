<?php

namespace App\Services\Interfaces;


interface IMessageService
{
    public function getMessages($id);

    public function createMessage($id, $isUser, $data);
}