<?php

namespace Mithra62\Tel\Extensions;

use ExpressionEngine\Service\Addon\Controllers\Extension\AbstractRoute;

class SetCpCss extends AbstractRoute
{
    public function process()
    {
        return file_get_contents(__DIR__ . '/../css/cp.css');
    }
}
