<?php

namespace App\BL\DataAccess\Type;

class T_DM_Thread_Media
{

    /**
     * @var string
     */
    public string $id;


    /**
     * email_incoming|email_outgoing
     *
     * @var string
     */
    public string $direction = "email_incoming";

    /**
     * @var string
     */
    public string $filename;

    /**
     * @var string
     */
    public string $date;

    /**
     * @var string
     */
    public string $ownerMessageId;

}
