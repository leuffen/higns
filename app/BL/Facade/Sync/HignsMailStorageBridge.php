<?php

namespace App\BL\Facade\Sync;

use App\BL\DataAccess\SubscriptionDataManager;
use App\BL\DataAccess\Type\T_DM_Thread_Message;
use Lack\MailScan\MailStorageInterface;
use PhpImap\IncomingMail;
use PhpImap\IncomingMailHeader;

class HignsMailStorageBridge implements MailStorageInterface
{

    public function __construct(protected SubscriptionDataManager $subscriptionDataManager)
    {
    }


    public function needsSync(IncomingMailHeader $mailHeader): bool
    {
        $threadMetaList = $this->subscriptionDataManager->getThreadMetaList();

        $threadMeta = $threadMetaList->getThreadByEMail($mailHeader->toString);
        if ($mailHeader->fromAddress === "matthias@leuffen.de" && $threadMeta === null) {
            $threadMeta = $threadMetaList->createThread();
            $threadMeta->partnerEMail[] = strtolower(trim($mailHeader->toString));
            $threadMeta->createdDate = date("Y-m-d H:i:s", strtotime($mailHeader->date));
            $threadMeta->title = $mailHeader->toString;
        }
        if ($threadMeta === null)
            return false;
        return true;
    }

    public function syncSingleMail(IncomingMail $mail): void
    {
        $threadMetaList = $this->subscriptionDataManager->getThreadMetaList();


        print_r ($threadMetaList);
        $threadMeta = $threadMetaList->getThreadByEMail($mail->toString);


        $threadMeta->lastMessageDate = date("Y-m-d H:i:s", strtotime($mail->date));

        $thread = $this->subscriptionDataManager->getThreadById($threadMeta->threadId);



        if ($thread->getMessageByImapId($mail->messageId) !== null)
            return;

        $message = new T_DM_Thread_Message(
            $mail->messageId,
            $mail->date,
            $mail->fromAddress,
            $mail->textPlain,
        );

        $thread->addMessage($message);
    }
}
