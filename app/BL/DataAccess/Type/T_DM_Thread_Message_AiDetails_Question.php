<?php

namespace App\BL\DataAccess\Type;

class T_DM_Thread_Message_AiDetails_Question
{

    /**
     * The Question
     *
     * @var string
     */
    public string $question;

    /**
     * The Answer (or leave blanc)
     *
     * @var string
     */
    public string $answer = "";

}
