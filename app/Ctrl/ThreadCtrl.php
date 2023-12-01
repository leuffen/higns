<?php

namespace App\Ctrl;

use App\BL\DataAccess\SubscriptionDataManager;
use App\Type\HignsConfig;

class ThreadCtrl
{

    public function __construct(
        public HignsConfig $hignsConfig,
        public SubscriptionDataManager $subscriptionDataManager
    ) {
    }








}
