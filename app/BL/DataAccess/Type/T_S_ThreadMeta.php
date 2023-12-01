<?php

namespace App\BL\DataAccess\Type;

class T_S_ThreadMeta
{

    /**
     * @var T_DM_ThreadMeta[]
     */
    public array $threads = [];


    public function getThreadByEMail(string $email) : ?T_DM_ThreadMeta {
        $email = strtolower(trim($email));
        foreach ($this->threads as $thread) {
            if (in_array($email, $thread->partnerEMail))
                return $thread;
        }
        return null;
    }

    public function createThread() : T_DM_ThreadMeta {
        $thread = new T_DM_ThreadMeta();
        $thread->threadId = uniqid();
        $this->threads[] = $thread;
        return $thread;
    }

}
