<?php namespace Lamoni\JSnapCommander\JSnapIODriver;

use Lamoni\JSnapCommander\JSnapConfig\JSnapConfigAbstract;

abstract class JSnapIODriverAbstract
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

    abstract public function save($deviceName, array $jSnapResults);

    abstract public function load($deviceName, $jSnapKey);

    abstract public function loadList($deviceName);

    public function generateCurrentKey($deviceName)
    {
        return $deviceName .
                "_" .
                $this->configIO->getConfigDataParameter('jSnapTime');
    }

    public function splitKey($key)
    {

        return explode("_", $key);

    }

    public function formatKey($deviceName, $jSnapTime)
    {
        return $deviceName.
        "_" .
        $jSnapTime;
    }

}