<?php

namespace AddUserDinoBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AddUserDinoBundle extends Bundle
{

    public function getParent()
    {
        return 'FOSUserBundle';
    }

}
