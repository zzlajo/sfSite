<?php

namespace Abroad\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AbroadUserBundle extends Bundle
{
    public function getParent() {
	return 'FOSUserBundle';
    }
}
