<?php namespace Lamoni\JSnapCommander\JSnapConfig\JSnapConfigIO;

use Lamoni\JSnapCommander\JSnapConfig\JSnapConfigAbstract;

class JSnapConfigIOFiles extends JSnapConfigAbstract
{

    static public function getConfigDefinition()
    {

        return array(
            'SnapshotSwapPath' => array(
                'validator' => 'is_dir',
                'header'    => 'Snapshot Swap Path',
                'example'   => '/var/www/html/swap/snapshots/',
                'type'      => 'text'
            ),
            'SnapCheckSwapPath' => array(
                'validator' => 'is_dir',
                'header'    => 'Snap Check Swap Path',
                'example'   => '/var/www/html/swap/snapchecks/',
                'type'      => 'text'
            )
        );

    }

}