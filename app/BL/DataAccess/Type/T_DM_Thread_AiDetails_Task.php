<?php

namespace App\BL\DataAccess\Type;

class T_DM_Thread_AiDetails_Task
{

    /**
     * Who has to work on this task
     *
     * Choose between "inbound" or "outbound"
     *
     * @var string
     */
    public string $partner = "";


    /**
     * Provide a short description of the task
     *
     * @var string
     */
    public string $taskName = "";

    /**
     * Provide detailed information needed to fulfill the task
     * @var string
     */
    public string $details = "";

    /**
     *
     *
     *
     * @var string
     */
    public string $status = "";

    /**
     * The Date this task was opened
     *
     * Format(YYYY-MM-DD)
     *
     * @var string
     */
    public string $dateOpen = "";

    /**
     * The date this task was closed
     *
     * Format(YYYY-MM-DD) or Empty string if not closed yet
     *
     * @var string
     */
    public string $dateClosed = "";

    /**
     * Deadline date for this task (Format YYYY-MM-DD). If no deadline was specified: The default deadline is 7 days after the task was opened
     * or 3 days after the task was last mentioned.
     *
     * @var string
     */
    public string $deadline = "";

}
