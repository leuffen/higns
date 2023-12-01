<?php

namespace app\BL\DataAccess\Type;

class T_DM_Mailbox
{

    /**
     * Example: "{mailX.leuffen.de:993/imap/ssl}"
     *
     * @var string
     */
    public string $serverName;

    /**
     * @var string
     */
    public string $userName;

    /**
     * @var string
     */
    public string $password;

}
