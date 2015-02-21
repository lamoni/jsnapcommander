<?php namespace Lamoni\JSnapCommander;

use Lamoni\JSnapCommander\JSnapIODriver\JSnapIODriverAbstract;
use Lamoni\JSnapCommander\JSnapTriggerDriver\JSnapTriggerDriverAbstract;

class JSnapCommander
{

    protected $triggerDriver;

    protected $ioDriver;

    public function __construct(JSnapTriggerDriverAbstract $triggerDriver, JSnapIODriverAbstract $ioDriver)
    {

        $this->triggerDriver = $triggerDriver;

        $this->ioDriver = $ioDriver;

    }

    public function snapShot($deviceName)
    {

        return array(
                'snapID' => $this->ioDriver->saveSnapshot($deviceName,
                    $this->triggerDriver->snapShot($deviceName)
                ),
                'error' => 0
            );
    }

    public function snapCheck($deviceName, $saveSnapCheck=false)
    {

        $snapCheck = $this->triggerDriver->snapCheck($deviceName);

        if ($saveSnapCheck) {

            $snapCheck = array (
                'snapID' => $this->ioDriver->saveSnapCheck($deviceName, $snapCheck),
                'error' => 0
            );

        }
        else {
            $snapCheck = $snapCheck->toArray();
        }

        return $snapCheck;

    }

    public function loadSnapCheck($jSnapKey)
    {

        list($deviceName, $jSnapTime) = $this->ioDriver->splitKey($jSnapKey);

        return $this->ioDriver->loadSnapCheck($deviceName, $jSnapTime);

    }

    public function check($deviceName, $presnapID, $postsnapID)
    {

        return $this->triggerDriver->check($deviceName,
            $this->ioDriver->loadSnapshot($deviceName, $presnapID),
            $this->ioDriver->loadSnapshot($deviceName, $postsnapID)
        )->toArray();

    }

    public function loadPreSnapList($deviceName)
    {
        $snapShots = $this->ioDriver->loadSnapshotList($deviceName);

        $jSnapSnapList = array();

        foreach ($snapShots as $snapName => $snapTimes) {

            foreach ($snapTimes as $snapTime) {

                $jSnapSnapList[$snapTime] = $snapName;

            }

        }

        array_pop($jSnapSnapList);

        return $jSnapSnapList;

    }

    public function loadPostSnapList($deviceName, $startTime)
    {

        $snapShots = $this->ioDriver->loadSnapshotList($deviceName);

        $jSnapSnapList = array();

        foreach ($snapShots as $snapName => $snapTimes) {

            foreach ($snapTimes as $snapTime) {

                if ($snapTime > $startTime) {

                    $jSnapSnapList[$snapTime] = $snapName;

                }

            }

        }

        return $jSnapSnapList;

    }

    public function getAllSnapshots($dateFormat = "Y-m-d H:i:s")
    {

        $snapshots = $this->ioDriver->loadSnapshotList("*");

        $resultSnapshots = [];

        foreach ($snapshots as $snapshotDeviceName => $snapshotTimes) {

            foreach ($snapshotTimes as $snapshotTime) {

                $resultSnapshots[$snapshotDeviceName][$snapshotTime] = date($dateFormat, $snapshotTime);

            }

        }

        return $resultSnapshots;
    }

    public function deleteSnapshot($deviceName, $snapTime)
    {

        return $this->ioDriver->deleteSnapshot($deviceName, $snapTime);

    }

    public function splitKey($key)
    {

        return $this->ioDriver->splitKey($key);

    }

}