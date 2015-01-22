<?php namespace JSnapCommander\JSnapTriggerDriver;

use JSnapCommander\JSnapConfig\JSnapConfigAbstract;
use JSnapCommander\JSnapSnapSectionBundle\JSnapSnapSectionBundle;

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

    abstract public function snap($deviceName);

    abstract public function check($deviceName, JSnapSnapSectionBundle $preSnap, JSnapSnapSectionBundle $postSnap);

}