<?php

namespace App\Ctrl;

use App\BL\DataAccess\SubscriptionDataManager;
use App\Type\HignsConfig;
use Brace\Router\Attributes\BraceRoute;

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






}
