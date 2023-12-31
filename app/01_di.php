<?php
namespace App;

use App\BL\DataAccess\DataAccessManager;
use App\Business\processors\DownloadStorageProcessor;
use App\Business\processors\ImageStorageProcessor;
use App\Business\processors\PdfStorageProcessor;
use App\Business\processors\SvgStorageProcessor;
use App\Business\StorageFacet;
use App\Config\MediaStoreConf;
use App\Config\MediaStoreSubscriptionInfo;
use App\Type\HignsConfig;
use App\Type\T_Config;
use Brace\Command\CommandModule;
use Brace\Core\AppLoader;
use Brace\Core\BraceApp;
use Brace\Dbg\BraceDbg;
use Brace\Mod\Request\Zend\BraceRequestLaminasModule;
use Brace\Router\RouterModule;
use Brace\Router\Type\RouteParams;
use Lack\Frontmatter\Repo\FrontmatterRepo;
use Lack\Keystore\KeyStore;
use Lack\OpenAi\LackOpenAiClient;
use Lack\OpenAi\Logger\CliLogger;
use Lack\OpenAi\Logger\NullLogger;
use Lack\Subscription\Brace\SubscriptionClientModule;
use Lack\Subscription\Type\T_Subscription;
use Phore\Di\Container\Producer\DiService;
use Phore\Di\Container\Producer\DiValue;
use Phore\ObjectStore\Driver\FileSystemObjectStoreDriver;
use Phore\ObjectStore\Driver\GoogleObjectStoreDriver;
use Phore\ObjectStore\ObjectStore;


BraceDbg::SetupEnvironment(true, ["192.168.178.20", "localhost", "localhost:5000"]);


AppLoader::extend(function () {
    $app = new BraceApp();

    // Use Laminas (ZendFramework) Request Handler
    $app->addModule(new BraceRequestLaminasModule());

    // Use the Uri-Based Routing
    $app->addModule(new RouterModule());
    $app->addModule(new CommandModule());


    $app->addModule(
        new SubscriptionClientModule(
            CONF_SUBSCRIPTION_ENDPOINT,
            CONF_SUBSCRIPTION_CLIENT_ID,
            CONF_SUBSCRIPTION_CLIENT_SECRET
        )
    );


    $app->define("dataAccessManager", new DiService(function () {
        $objectStore = new ObjectStore(new FileSystemObjectStoreDriver(CONF_DATA_DIR));

        return new DataAccessManager($objectStore);
    }));

    $app->define("hignsConfig", new DiService(function (DataAccessManager $dataAccessManager, T_Subscription $subscription, RouteParams $routeParams) {
        $subscriptionId = $routeParams->get("subscription_id");

        return new HignsConfig($dataAccessManager->getDataAccessObjectForSubscription($subscriptionId));
    }));

    $app->define("subscriptionDataManager", new DiService(function (DataAccessManager $dataAccessManager, T_Subscription $subscription) {

        return $dataAccessManager->getDataAccessObjectForSubscription($subscription->subscription_id);
    }));

    $app->define("openAiClient", new DiService(function () {
        $openAiClient = new LackOpenAiClient(KeyStore::Get()->getAccessKey("open_ai"), new CliLogger());
        return $openAiClient;
    }));

    // Define the app so it is also available in dependency-injection
    $app->define("app", new DiValue($app));


    return $app;
});
