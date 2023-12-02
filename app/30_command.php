<?php
namespace App;


use App\BL\DataAccess\DataAccessManager;
use App\BL\Facade\SyncMailFacade;
use Brace\Core\AppLoader;
use Brace\Core\BraceApp;
use Lack\MailScan\Config\ImapMailboxConfig;
use Phore\ObjectStore\ObjectStore;

AppLoader::extend(function (BraceApp $app) {
    $app->command->addCommand("scan", function (DataAccessManager $dataAccessManager) {

        $d = $dataAccessManager->getDataAccessObjectForSubscription("demo1");

        $syncMailFacade = new SyncMailFacade($d);
        $mailboxConfig = ImapMailboxConfig::LoadFromKeystore("/opt/.keystore.yml");
        $syncMailFacade->syncMailbox($mailboxConfig);

        $d->storeThreadMetaList();

    });
});


