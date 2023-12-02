<?php

namespace App\BL\DataAccess\Type;

class T_DM_Thread
{

    public function __construct(
        /**
         * @var string
         */
        public ?string $threadId
    )
    {}


    /**
     * @var array
     */
    public array $status = [];

    /**
     * @var T_DM_Thread_Message[]
     */
    public array $messages = [];

    /**
     * @var array
     */
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
