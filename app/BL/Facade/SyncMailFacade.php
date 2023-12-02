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

        // Syncronize outgoing emails to create new threads
        echo "\nSyncing outgoing emails...";
        $syncFacet->syncMailbox(new HignsMailStorageBridge($this->subscriptionDataManager, true), $driver->mailboxConfig->sentFolder);

        echo "\nSyncing incoming emails...";
        $syncFacet->syncMailbox(new HignsMailStorageBridge($this->subscriptionDataManager, false), $driver->mailboxConfig->inboxFolder);


        $this->subscriptionDataManager->storeThreadMetaList();


    }


}
