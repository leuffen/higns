<?php

namespace App\BL\DataAccess;

use Phore\ObjectStore\ObjectStore;

class DataAccessManager
{


    public function __construct(public ObjectStore $objectStore) {

    }

    public function getDataAccessObjectForSubscription(string $subscriptionId) : SubscriptionDataManager {
        return new SubscriptionDataManager($this->objectStore, $subscriptionId);
    }

}
