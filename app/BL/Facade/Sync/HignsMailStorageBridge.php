<?php

namespace App\BL\Facade\Sync;

use App\BL\DataAccess\SubscriptionDataManager;
use App\BL\DataAccess\Type\T_DM_Thread_Message;
use Lack\MailScan\MailStorageInterface;
use PhpImap\IncomingMail;
use PhpImap\IncomingMailHeader;

class HignsMailStorageBridge implements MailStorageInterface
{

    public function __construct(

        protected SubscriptionDataManager $subscriptionDataManager,

        /**
         * If true, the mailbox is treaded as Send mails - so create a new thread for each mail
         *
         * @var bool
         */
        protected bool $isSent = false)
    {
    }


    public function needsSync(IncomingMailHeader $mailHeader): bool
    {
        $threadMetaList = $this->subscriptionDataManager->getThreadMetaList();


        if($this->isSent) {
            echo "SENT MODE";
            // Syncronize all outgoing mails
            $normalizedEMail = phore_email($mailHeader->toString)->getEMailNormalized();
            $threadMeta = $threadMetaList->getThreadByEMail($normalizedEMail);

            if ($threadMeta !== null)
                return true;

            $threadMeta = $threadMetaList->createThread();
            $threadMeta->partnerEMail = [$normalizedEMail];
            $threadMeta->createdDate = date("Y-m-d H:i:s", strtotime($mailHeader->date));
            $threadMeta->title = $mailHeader->toString;

            return true; // Sync all sent mails
        }




        $normalizedEMail = phore_email($mailHeader->fromAddress)->getEMailNormalized();

        if ($normalizedEMail === null)
            throw new \InvalidArgumentException("Invalid From E-Mail: " . $mailHeader->toString);

        // Only sync existing threads for incoming mails
        $threadMeta = $threadMetaList->getThreadByEMail($normalizedEMail);
        if ($threadMeta === null)
            return false;
        return true;
    }

    public function syncSingleMail(IncomingMail $mail): void
    {
        $threadMetaList = $this->subscriptionDataManager->getThreadMetaList();


        if ($this->isSent) {
            $threadMeta = $threadMetaList->getThreadByEMail(phore_email($mail->toString)->getEMailNormalized());
        } else {
            $threadMeta = $threadMetaList->getThreadByEMail(phore_email($mail->fromAddress)->getEMailNormalized());
        }

        $thread = $this->subscriptionDataManager->getThreadById($threadMeta->threadId);



        if ($thread->getMessageByImapId($mail->messageId) !== null)
            return;

        $message = new T_DM_Thread_Message(
            $mail->messageId,
            (string)$mail->subject,
            $mail->date,
            $mail->fromAddress,
            $this->isSent ? "email_outgoing" : "email_incoming",
            $mail->textPlain,
        );

        echo "SAVING!";
        $thread->addMessage($message);
        $this->subscriptionDataManager->setThread($thread);
    }
}
