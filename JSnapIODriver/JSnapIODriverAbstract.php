<?php namespace Lamoni\JSnapCommander\JSnapIODriver;

use Lamoni\JSnapCommander\JSnapConfig\JSnapConfigAbstract;
use Lamoni\JSnapCommander\JSnapResults\JSnapResults;

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

    abstract public function saveSnapshot($deviceName, array $jSnapResults);

    abstract public function saveSnapCheck($deviceName, JSnapResults $jSnapResults);

    abstract public function loadSnapshot($deviceName, $jSnapKey);

    abstract public function loadSnapCheck($deviceName, $jSnapKey);

    abstract public function loadSnapshotList($deviceName);

    abstract public function deleteSnapshot($deviceName, $jSnapKey);

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