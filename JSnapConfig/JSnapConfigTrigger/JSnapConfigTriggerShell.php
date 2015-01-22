<?php namespace JSnapCommander\JSnapConfig\JSnapConfigTrigger;

use JSnapCommander\JSnapConfig\JSnapConfigAbstract;

class JSnapConfigTriggerShell extends JSnapConfigAbstract
{

    public function __construct($configData)
    {

        parent::__construct($configData);

        $this->configData['JSnapExecutablePath'] = dirname(
            $this->getConfigDataParameter('JSnapExecutable')
        );

        $this->configData['JuiseExecutablePath'] = dirname(
            $this->getConfigDataParameter('JuiseExecutable')
        );

    }

    static public function getConfigDefinition()
    {

        return array(
            'JSnapExecutable'   => 'is_executable',
            'ConfigFile'        => 'is_readable',
            'JuiseExecutable'   => 'is_executable',
            'SwapPath'          => 'is_dir',
            'DeviceUsername'    => 'is_string',
            'DevicePassword'    => 'is_string'
        );

    }
}