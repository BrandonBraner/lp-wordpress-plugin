<?php

namespace TheLoop\ServiceContainer;

use Pimple\Container;

class ServiceContainer
{
    public $ioc;

    public function __construct()
    {
        $this->ioc = new Container();

    }

    public function getContainer()
    {
        return $this->ioc;
    }
}

$container = new ServiceContainer();
$ioc = $container->getContainer();

/**
 * include providers from App
 */
require_once(dirname(dirname(dirname(__FILE__))).'/App/Config/RegisterProviders.php');
