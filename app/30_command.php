<?php
namespace App;


use App\BL\DataAccess\DataAccessManager;
use App\BL\Facade\AnalyserFacet;
use App\BL\Facade\SyncMailFacade;
use Brace\Command\CliValueArgument;
use Brace\Core\AppLoader;
use Brace\Core\BraceApp;
use Lack\MailScan\Config\ImapMailboxConfig;
use Lack\OpenAi\LackOpenAiClient;
use Phore\ObjectStore\ObjectStore;

AppLoader::extend(function (BraceApp $app) {
    $app->command->addCommand("scan", function (DataAccessManager $dataAccessManager) {

        $d = $dataAccessManager->getDataAccessObjectForSubscription("demo1");

        $syncMailFacade = new SyncMailFacade($d);
        $mailboxConfig = ImapMailboxConfig::LoadFromKeystore("/opt/.keystore.yml");
        $syncMailFacade->syncMailbox($mailboxConfig);

        $d->storeThreadMetaList();

    });

    $app->command->addCommand("analyze", function (DataAccessManager $dataAccessManager, LackOpenAiClient $openAiClient, array $arguments = []) {

        $d = $dataAccessManager->getDataAccessObjectForSubscription("demo1");


        print_r ($arguments);
        $analyzerFacet = new AnalyserFacet($d, $openAiClient);

        if ($arguments["--thread_id"] !== null) {
            $analyzerFacet->analyzeThread($arguments["--thread_id"], true);
            return;
        }
        $analyzerFacet->analyzeAllThreads();

        $d->storeThreadMetaList();

    }, "", [new CliValueArgument("--thread_id", "Thread ID to analyze")]);

    $app->command->addCommand("analyzeHistory", function (DataAccessManager $dataAccessManager, LackOpenAiClient $openAiClient, array $arguments = []) {

        $d = $dataAccessManager->getDataAccessObjectForSubscription("demo1");


        print_r ($arguments);
        $analyzerFacet = new AnalyserFacet($d, $openAiClient);

        if ($arguments["--thread_id"] !== null) {
            $analyzerFacet->analyzeHistory($arguments["--thread_id"], true);
            return;
        }
        //$analyzerFacet->analyzeAllThreads();

        $d->storeThreadMetaList();

    }, "", [new CliValueArgument("--thread_id", "Thread ID to analyze")]);
});


