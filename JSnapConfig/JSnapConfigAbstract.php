<?php namespace Lamoni\JSnapCommander\JSnapConfig;

abstract class JSnapConfigAbstract
{

    protected $configData = array();

    protected $acceptableConfigData = array();

    public function __construct(array $configData)
    {

        $this->configData = $configData;

        $this->configData['jSnapTime'] = time();

        $this->acceptableConfigData = static::getConfigDefinition();

        $this->acceptableConfigData['jSnapTime'] = 'is_numeric';

        /**
         * Validate the inputted configData against the inputted acceptableConfigData
         */
        if (count($this->configData) !== count($this->acceptableConfigData)) {

            throw new \Exception("JSnapConfigData validation failed: mismatched number of inputted config items");

        }

        foreach ($configData as $configDataKey => $configDataValue) {

            if (!$this->acceptableConfigData[$configDataKey]['validator']($configDataValue)) {

                throw new \Exception(
                    "JSnapConfigData parameter {$configDataKey} failed validation"
                );

            }

        }

    }

    public function getConfigData()
    {

        return $this->configData;

    }

    public function getConfigDataParameter($parameterName)
    {
        if (isset($this->configData[$parameterName])) {

            return $this->configData[$parameterName];

        }

        throw new \Exception("getConfigDataParameter failed: attempted to get non-existent configData item");

    }

    static public function getConfigDefinition()
    {

        return array();

    }


}