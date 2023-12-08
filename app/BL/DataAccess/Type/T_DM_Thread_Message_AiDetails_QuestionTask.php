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
     * Who is responsible for answering or performing the task.
     *
     * Values: "sender" or "receiver"
     *
     * @var string
     */
    public string $party = "receiver";

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
