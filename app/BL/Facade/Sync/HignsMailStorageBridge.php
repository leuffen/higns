<?php

namespace App\BL\Facade\Sync;

use App\BL\DataAccess\SubscriptionDataManager;
use App\BL\DataAccess\Type\T_DM_Thread_Media;
use App\BL\DataAccess\Type\T_DM_Thread_Message;
use Lack\MailScan\MailStorageInterface;
use PhpImap\IncomingMail;
use PhpImap\IncomingMailHeader;


function html2text($htmlContent) {
    // Replace opening <p> tags with a newline
    $plainText = preg_replace('/<p>/i', "\n", $htmlContent);
    $plainText = preg_replace('/<div>/i', "\n", $htmlContent);

    // Replace closing </p> tags with two newlines (one for closing, one as a spacer)
    $plainText = preg_replace('/<\/p>/i', "\n\n", $plainText);
    $plainText = preg_replace('/<\/div>/i', "\n\n", $plainText);

    // Remove Other HTML Tags
    $plainText = strip_tags($plainText);

    // Decode HTML Entities
    $plainText = html_entity_decode($plainText);

    // Handle Extra White Spaces
    // Remove leading and trailing white spaces and ensure only single blank lines between paragraphs
    $plainText = trim(preg_replace("/[\r\n]+/", "\n\n", $plainText));

    return $plainText;
}


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

        $messageDate = date("Y-m-d H:i:s", strtotime($mail->date));

        if ($this->isSent) {
            $threadMeta = $threadMetaList->getThreadByEMail(phore_email($mail->toString)->getEMailNormalized());
            if ($threadMeta->lastOutboundDate === null || $messageDate > $threadMeta->lastOutboundDate)
                $threadMeta->lastOutboundDate = $messageDate;
        } else {
            $threadMeta = $threadMetaList->getThreadByEMail(phore_email($mail->fromAddress)->getEMailNormalized());
            if ($threadMeta->lastInboundDate === null || $messageDate > $threadMeta->lastInboundDate)
                $threadMeta->lastInboundDate = $messageDate;
        }

        $thread = $this->subscriptionDataManager->getThreadById($threadMeta->threadId);






        if ($thread->getMessageByImapId($mail->messageId) !== null)
            return;


        $plainText = $mail->textPlain;
        if (trim ($plainText) === "") {
            $plainText = html2text($mail->textHtml);

        }

        $message = new T_DM_Thread_Message(
            $mail->messageId,
            (string)$mail->subject,
            $mail->date,
            $mail->fromAddress,
            $this->isSent ? "email_outgoing" : "email_incoming",
            $plainText,
        );

        if ($message->type === "email_incoming") {
            $threadMeta->isUnread = true;
            if ($threadMeta->isArchived)
                $threadMeta->isArchived = false;
        }

        $attachments = $mail->getAttachments();

        foreach ($attachments as $attachment) {
            $id = $this->subscriptionDataManager->addThreadAttachment($thread->threadId, $attachment->getContents());

            foreach ($thread->media as $media) {
                if ($media->filename === $attachment->name) {
                    $media->id = $id;
                    continue 2;
                }
            }
            $media = new T_DM_Thread_Media();
            $media->id = $id;
            $media->date = $message->dateTime;
            $media->filename = $attachment->name;
            $media->ownerMessageId = $message->imapId;
            $thread->media[] = $media;
        }

        echo "SAVING!";
        $thread->addMessage($message);
        $this->subscriptionDataManager->setThread($thread);
    }
}
