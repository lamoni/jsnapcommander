<?php namespace Lamoni\JSnapCommander\JSnapConfig\JSnapConfigIO;

use Lamoni\JSnapCommander\JSnapConfig\JSnapConfigAbstract;

class JSnapConfigIOFiles extends JSnapConfigAbstract
{

    static public function getConfigDefinition()
    {

        return array(
            'SwapPath' => array(
                'validator' => 'is_dir',
                'header'    => 'Swap Path',
                'example'   => '/var/www/html/swap',
                'type'      => 'text'
            )
        );

    }

}