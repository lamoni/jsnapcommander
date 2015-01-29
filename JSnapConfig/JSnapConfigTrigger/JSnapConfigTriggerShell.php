<?php namespace Lamoni\JSnapCommander\JSnapConfig\JSnapConfigTrigger;

use Lamoni\JSnapCommander\JSnapConfig\JSnapConfigAbstract;

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
            'JSnapExecutable' => array(
                'validator' => 'is_executable',
                'header'    => 'JSnap Executable',
                'example'   => '/usr/jawa/bin/jsnap',
                'type'      => 'text'
            ),
            'ConfigFile'      => array(
                'validator' => 'is_readable',
                'header'    => 'JSnap Config File',
                'example'   => '/usr/jawa/jsnap/samples/sample.conf',
                'type'      => 'text'
            ),
            'JuiseExecutable' => array(
                'validator' => 'is_executable',
                'header'    => 'JUISE Executable',
                'example'   => '/usr/jawa/bin/juise',
                'type'      => 'text'
            ),
            'SwapPath'       => array(
                'validator' => 'is_dir',
                'header'    => 'Swap Path',
                'example'   => '/var/www/html/swap',
                'type'      => 'text'
            ),
            'DeviceUsername'=> array(
                'validator' => 'is_string',
                'header'    => 'Device Username',
                'example'   => 'lamoni',
                'type'      => 'text'
            ),
            'DevicePassword'=> array(
                'validator' => 'is_string',
                'header'    => 'Device Password',
                'example'   => 'secr3t!',
                'type'      => 'password'
            ),
            'SnapCommandLineArguments'=> array(
                'validator' => 'is_string',
                'header'    => 'Snap Command-line Arguments',
                'example'   => '--nostricthostkeycheck',
                'type'      => 'text'
            ),
            'CheckCommandLineArguments'=> array(
                'validator' => 'is_string',
                'header'    => 'Check Command-line Arguments',
                'example'   => '--xml',
                'type'      => 'text'
            )

        );

    }
}
