<?php

namespace App\BL\DataAccess;

use App\BL\DataAccess\Type\T_DM_Mailbox;
use App\BL\DataAccess\Type\T_DM_Thread;
use App\BL\DataAccess\Type\T_S_ThreadMeta;
use Phore\ObjectStore\ObjectStore;
use Phore\ObjectStore\Type\ObjectStoreObject;

class SubscriptionDataManager
{

    protected ObjectStoreObject $mailboxListFile;

    public function __construct(public ObjectStore $objectStore, public string $subscriptionId) {
        $this->mailboxListFile = $this->objectStore->object("subscription/$subscriptionId/mailboxList.json");
        $this->threadMetaListFile = $this->objectStore->object("subscription/$subscriptionId/threadMetaList.json");

    }



    /**
     * @return T_DM_Mailbox[]
     */
    public function getMailboxList() : array {
        return $this->mailboxListFile->getJson();
    }

    /**
     * @param T_DM_Mailbox[] $mailboxList
     */
    public function setMailboxList(array $mailboxList) {
        $this->mailboxListFile->putJson($mailboxList);
    }


    private T_S_ThreadMeta|null $threadMetaObject = null;
    public function getThreadMetaList() : T_S_ThreadMeta {
        if ($this->threadMetaObject === null) {
            if ( ! $this->threadMetaListFile->exists())
                $this->threadMetaObject = new T_S_ThreadMeta();
            else
                $this->threadMetaObject = $this->threadMetaListFile->getJson(T_S_ThreadMeta::class);
        }

        return $this->threadMetaObject;
    }

    public function storeThreadMetaList() {
        if ($this->threadMetaObject === null)
            throw new \InvalidArgumentException("No thread meta object set");
        $this->threadMetaListFile->putJson((array)$this->threadMetaObject);
    }


    public function getThreadById(string $threadId) : T_DM_Thread {
        $thread = $this->objectStore->object("subscription/$this->subscriptionId/thread/$threadId/thread.json");
        if ( ! $thread->exists())
            return new T_DM_Thread($threadId);
        return $thread->getJson(T_DM_Thread::class);
    }

    public function setThread(T_DM_Thread $thread) {
        $thread->lastUpdate = date_create()->format(DATE_ATOM);
        $this->objectStore->object("subscription/$this->subscriptionId/thread/{$thread->threadId}/thread.json")->putJson((array)$thread);
    }


    /**
     * Store data to blob storage and return contentId
     * @param string $content
     * @return string
     */
    public function getThreadBlob(string $threadId) : string {

    }

    /**
     * Get content from blob storage
     * @param string $contentId
     * @return string
     */
    public function getBlob(string $contentId) : string {

    }

}
