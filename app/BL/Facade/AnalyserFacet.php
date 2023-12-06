<?php

namespace App\BL\Facade;

use App\BL\DataAccess\SubscriptionDataManager;
use App\BL\DataAccess\Type\T_DM_Thread_AiDetails;
use App\BL\DataAccess\Type\T_DM_Thread_Message_AiDetails;
use Lack\OpenAi\Helper\JsonSchemaGenerator;
use Lack\OpenAi\LackOpenAiClient;
use Lack\OpenAi\LackOpenAiFacet;

class AnalyserFacet
{
    public function __construct(
        public SubscriptionDataManager $subscriptionDataManager,
        public LackOpenAiClient $openAiClient
    ){}


    public function analyzeThread (string $threadId, bool $force = false)
    {
        $thread = $this->subscriptionDataManager->getThreadById($threadId);

        foreach ($thread->messages as $message) {
            if ($message->aiDetails !== null && ! $force)
                continue;


            $aiDetails = $this->openAiClient->getFacet()->promptData(__DIR__ . "/prompt/prompt-compact-single-message.txt", [
                "message" => $message->originalText,
                "from" => $message->from,
                "direction" => $message->type,
                "subject" => $message->subject,

            ], T_DM_Thread_Message_AiDetails::class);


            $message->aiDetails = $aiDetails;
        }

        $this->subscriptionDataManager->setThread($thread);
    }


    public function analyzeHistory(string $threadId, bool $force = false)
    {
        $thread = $this->subscriptionDataManager->getThreadById($threadId);

        $thread->sortMessages();;

        $data = [];
        foreach ($thread->messages as $message) {
            $mailData = [
                "message" => $message->originalText,
                "from" => $message->from,
                "date" => $message->dateTime,
                "type" => $message->type,
                "subject" => $message->subject,
            ];
            $data[] = $mailData;
        }

        //print_r ((new JsonSchemaGenerator())->convertToJsonSchema(T_DM_Thread_AiDetails::class));
        //exit;

        $facet = $this->openAiClient->getFacet();
        $facet->setModel("gpt-4-1106-preview");

        $facet->promptData(__DIR__ . "/prompt/prompt-history.txt", [
            "messages" => phore_json_encode($data),
        ], T_DM_Thread_AiDetails::class);
        $this->subscriptionDataManager->setThread($thread);
    }

    public function analyzeAllThreads (bool $force = false)
    {
        $tml = $this->subscriptionDataManager->getThreadMetaList();

        foreach ($tml->threads as $thread) {
            $this->analyzeThread($thread->threadId, $force);
        }
    }


}
