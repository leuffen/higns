<?php

namespace app\BL\DataAccess\Type;

class T_DM_Thread_Message
{

    public function __construct(

          public string $imapId,

        public string $subject,

          public string $dateTime,

        public string $originalText,
    )
    {}



    public string $direction;

    public string $shortMessage;

}
