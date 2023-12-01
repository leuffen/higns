<?php

namespace App\BL\DataAccess\Type;

class T_DM_Thread
{

    public function __construct(
        public ?string $threadId
    )
    {}



    public array $status = [];

    public array $messages = [];

    public array $media = [];


    public function addMessage(T_DM_Thread_Message $message) {
        $this->messages[] = $message;
    }

    public function getMessageByImapId(string $imapId) : ?T_DM_Thread_Message {
        foreach ($this->messages as $message) {
            if ($message->imapId === $imapId)
                return $message;
        }
        return null;
    }

}
