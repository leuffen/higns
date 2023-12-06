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
     * All Tasks that are relevant for the inbound or outbound partner
     *
     * @var T_DM_Thread_AiDetails_Task[]
     */
    public array $tasks = [];

}
