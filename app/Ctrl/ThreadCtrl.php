<?php

namespace App\Ctrl;

use App\BL\DataAccess\SubscriptionDataManager;
use App\Type\HignsConfig;
use Brace\Core\BraceApp;
use Brace\Router\Attributes\BraceRoute;
use Brace\Router\Type\RouteParams;
use http\Message\Body;
use Laminas\Diactoros\Request;
use Laminas\Diactoros\ServerRequest;

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

    #[BraceRoute("POST@/{subscription_id}/thread/{thread_id}/setThreadMetaField()", "setthreadmetafield")]
    public function setThreadMetaField(RouteParams $routeParams, array $body) {
        $tml = $this->subscriptionDataManager->getThreadMetaList();
        foreach ($tml->threads as $thread) {
            if ($thread->threadId !== $routeParams->get("thread_id")) {
                continue;
            }
            foreach ($body as $key => $value) {
                $thread->$key = $value;
            }
            $this->subscriptionDataManager->storeThreadMetaList();
            return $thread;

        }
    }


    #[BraceRoute("GET@/{subscription_id}/thread/{thread_id}/download/{media_id}", "downloadmedia")]
    public function downloadMedia(BraceApp $app, RouteParams $routeParams, ServerRequest $request) {
        $download =  ($request->getQueryParams()["download"] ?? null) === "true";

        $thread = $this->subscriptionDataManager->getThreadById($routeParams->get("thread_id"));
        $mediaMeta = $thread->getMediaById($routeParams->get("media_id"));

        $data = $this->subscriptionDataManager->getThreadAttachment($routeParams->get("thread_id"), $routeParams->get("media_id"));


        $mime = [
            "jpg" => "image/jpeg",
            "jpeg" => "image/jpeg",
            "png" => "image/png",
            "mp4" => "video/mp4",
            "svg" => "image/svg+xml",
            "gif" => "image/gif",
            "pdf" => "application/pdf",
            "txt" => "text/plain",
            "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "html" => "text/plain", // Important! Never open HTML on same server url!
        ];

        $contentType = $mime[strtolower(phore_file($mediaMeta->filename)->getExtension())] ?? "application/octet-stream";

        $dispo = [];
        if ($download) {
            $contentType = "application/octet-stream";
            $dispo["Content-Disposition"] = "attachment; filename=\"" . $mediaMeta->filename . "\"";
        }

        $response = $app->responseFactory->createResponseWithBody($data, 200, [
            "Content-Type" => $contentType,

            ...$dispo,
        ]);
        return $response;
    }


}
