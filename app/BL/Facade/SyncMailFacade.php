<?php

namespace App\BL\Facade;

use App\BL\DataAccess\DataAccessManager;
use App\BL\DataAccess\SubscriptionDataManager;
use App\BL\Facade\Sync\HignsMailStorageBridge;
use Lack\MailScan\Config\ImapDriver;
use Lack\MailScan\Config\ImapMailboxConfig;
use Lack\MailScan\MailScanFacet;

class SyncMailFacade
{

    public function __construct(
        public SubscriptionDataManager $subscriptionDataManager,
    ){}


    public function syncMailbox (ImapMailboxConfig $mailboxConfig)
    {

        $driver = new ImapDriver();
        $driver->connect($mailboxConfig);

        $syncFacet = new MailScanFacet($driver);
        $syncFacet->syncMailbox(new HignsMailStorageBridge($this->subscriptionDataManager));


        $this->subscriptionDataManager->storeThreadMetaList();


    }


}
