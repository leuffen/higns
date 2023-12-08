<?php

namespace App\BL\DataAccess\Type;

class T_DM_Thread_AiDetails
{

    /**
     * All relevant Information like name, address, phone number, etc.
     * regarding the inbound partner
     *
     * @var string
     */
    public string $inboundPartnerData = "";


    /**
     * Provide a short overview of the current state of the communication. Who is waiting for what? What is already done?
     * What is the next step? What is the current status? etc.
     *
     * @var string
     */
    public string $statusSummary = "";


    /**
     * All Tasks that are relevant for the inbound or outbound partner
     *
     * @var T_DM_Thread_AiDetails_Task[]
     */
    public array $tasks = [];

}
