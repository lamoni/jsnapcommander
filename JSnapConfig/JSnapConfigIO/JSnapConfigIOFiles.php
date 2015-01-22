<?php namespace JSnapCommander\JSnapConfig\JSnapConfigIO;

use JSnapCommander\JSnapConfig\JSnapConfigAbstract;

class JSnapConfigIOFiles extends JSnapConfigAbstract
{

    static public function getConfigDefinition()
    {

        return array(
            'SwapPath' => 'is_dir',
        );

    }

}