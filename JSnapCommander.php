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

    public function snap($deviceName)
    {

        return json_encode(
            array(
                'snapID' => $this->ioDriver->save($deviceName,
                    $this->triggerDriver->snap($deviceName)
                ),
                'error' => 0
            )
        );

    }

    public function snapCheck($deviceName)
    {

        return $this->triggerDriver->snapCheck($deviceName)->toArray();

    }

    public function check($deviceName, $presnapID, $postsnapID)
    {

        return $this->triggerDriver->check($deviceName,
            $this->ioDriver->load($deviceName, $presnapID),
            $this->ioDriver->load($deviceName, $postsnapID)
        )->toArray();

    }

    public function loadPreSnapList($deviceName)
    {
        $snapShots = $this->ioDriver->loadList($deviceName);

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

        $snapShots = $this->ioDriver->loadList($deviceName);

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

}