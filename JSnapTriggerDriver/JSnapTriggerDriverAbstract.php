<?php namespace Lamoni\JSnapCommander\JSnapTriggerDriver;

use Lamoni\JSnapCommander\JSnapConfig\JSnapConfigAbstract;
use Lamoni\JSnapCommander\JSnapSnapSectionBundle\JSnapSnapSectionBundle;

abstract class JSnapTriggerDriverAbstract
{

    protected $configIO;

    public function __construct(JSnapConfigAbstract $configIO)
    {

        $this->configIO = $configIO;

    }

    public function getConfigIO()
    {

        return $this->configIO;

    }

    static public function getConfigDefinition()
    {

        return static::getConfigIO()->getConfigDefinition();

    }

    abstract public function snap($deviceName);

    abstract public function check($deviceName, JSnapSnapSectionBundle $preSnap, JSnapSnapSectionBundle $postSnap);

    abstract public function snapCheck($deviceName);

}