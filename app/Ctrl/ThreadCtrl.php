<?php

namespace App\Ctrl;

use App\BL\DataAccess\SubscriptionDataManager;
use App\Type\HignsConfig;
use Brace\Router\Attributes\BraceRoute;
use Brace\Router\Type\RouteParams;

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




}
