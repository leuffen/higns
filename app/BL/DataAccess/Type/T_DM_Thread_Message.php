<?php

namespace app\BL\DataAccess\Type;

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
    )
    {}


    /**
     * @var string|null
     */
    public ?string $shortMessage;

}
