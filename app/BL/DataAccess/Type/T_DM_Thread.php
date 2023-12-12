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
     * @var T_DM_Thread_Media[]
     */
    public array $media = [];


    /**
     * @var string|null
     */
    public ?string $statusMsg = null;

    /**
     * @var T_DM_Thread_AiDetails|null
     */
    public $aiDetails = null;


    public function sortMessages() {
        usort($this->messages, fn(T_DM_Thread_Message $a, T_DM_Thread_Message $b) => $a->dateTime > $b->dateTime ? 1 : -1);
    }

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
