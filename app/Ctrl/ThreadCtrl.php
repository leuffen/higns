<?php

namespace App\Ctrl;

use App\BL\DataAccess\SubscriptionDataManager;
use App\Type\HignsConfig;
use Brace\Router\Attributes\BraceRoute;
use Brace\Router\Type\RouteParams;
use http\Message\Body;

class ThreadCtrl
{

    public function __construct(
        public HignsConfig $hignsConfig,
        public SubscriptionDataManager $subscriptionDataManager
    ) {
    }



    #[BraceRoute("GET@/{subscription_id}/getThreadList()", "getthreadlist")]
    public function getThreadList() {
        return $this->subscriptionDataManager->getThreadMetaList();
    }

     #[BraceRoute("GET@/{subscription_id}/thread/{thread_id}/getThread()", "getthreadmessages")]
    public function getThreadMessages(RouteParams $routeParams) {
        $thread = $this->subscriptionDataManager->getThreadById($routeParams->get("thread_id"));
        $thread->sortMessages();
        return $thread;
    }

    #[BraceRoute("POST@/{subscription_id}/thread/{thread_id}/setThreadMetaField()", "setthreadmetafield")]
    public function setThreadMetaField(RouteParams $routeParams, array $body) {
        $tml = $this->subscriptionDataManager->getThreadMetaList();
        foreach ($tml->threads as $thread) {
            if ($thread->threadId !== $routeParams->get("thread_id")) {
                continue;
            }
            foreach ($body as $key => $value) {
                $thread->$key = $value;
            }
            $this->subscriptionDataManager->storeThreadMetaList();
            return $thread;

        }
    }



}
