<?php

namespace App\BL\DataAccess\Type;

class T_DM_Thread_Message
{

    public function __construct(

        public string $imapId,
        public string $subject,
        public string $dateTime,
        public string $from,
        /**
         * email_incoming|email_outgoing|note
         *
         * @var string
         */
        public string $type,

        public string $originalText,

        /**
         * List of file Attachements
         *
         * @var string[]|null
         */
        public array|null $attachmentFileList = null
    )
    {}


    /**
     * @var string|null
     */
    public ?string $shortMessage;

    /**
     * @var T_DM_Thread_Message_AiDetails|null
     */
    public ?T_DM_Thread_Message_AiDetails $aiDetails = null;

}
