<?php

namespace App\BL\DataAccess\Type;

class T_DM_ThreadMeta
{

    /**
     * @var string
     */
    public string $threadId;

    /**
     * The user defined Title of this thread
     *
     * @var string
     */
    public string $title;

    /**
     * List of e-Mail addresses of the partners
     *
     * @var string[]
     */
    public array $partnerEMail;


    public string $createdDate;

    /**
     * @var string|null
     */
    public ?string $lastInboundDate = null;

    /**
     * @var string|null
     */
    public ?string $lastOutboundDate = null;


    /**
     * @var bool
     */
    public bool $isHidden = false;


}
