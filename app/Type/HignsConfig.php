<?php

namespace App\Type;

use App\BL\DataAccess\DataAccessManager;
use App\BL\DataAccess\SubscriptionDataManager;

class HignsConfig
{


    public function __construct(public readonly SubscriptionDataManager $subscriptionDataManager) {

    }

}
