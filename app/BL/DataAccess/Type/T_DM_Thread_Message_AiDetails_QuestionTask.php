<?php

namespace App\BL\DataAccess\Type;

class T_DM_Thread_Message_AiDetails_QuestionTask
{

    /**
     * Is this a question or an task?
     *
     * Value: "question" or "task"
     *
     * @var string
     */
    public string $type = "question";

    /**
     * The Question or Task-Description
     *
     * @var string
     */
    public string $subject = "";

    /**
     * The Answer / Comment  (leave blanc - provided by user)
     *
     * @var string
     */
    public string $response = "";

}
