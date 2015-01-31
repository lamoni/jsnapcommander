<?php namespace Lamoni\JSnapCommander\JSnapIODriver;

use Lamoni\JSnapCommander\JSnapHelpers\JSnapHelpers;
use Lamoni\JSnapCommander\JSnapResults\JSnapResults;
use Lamoni\JSnapCommander\JSnapSnapSectionBundle\JSnapSnapSectionBundle;
use Lamoni\JSnapCommander\JSnapSnapSectionBundle\JSnapSnapSection\JSnapSnapSectionXML;

class JSnapIODriverFiles extends JSnapIODriverAbstract
{

    public function saveSnapshot($deviceName, array $jSnapResults)
    {

        extract($this->configIO->getConfigData());

        $finalSnapContent = serialize($jSnapResults);

        if (file_put_contents(
                $SnapshotSwapPath . "/" . $this->generateCurrentKey($deviceName), $finalSnapContent
            ) !== false) {

            return $this->generateCurrentKey($deviceName);

        }
        else {

            throw new \Exception(__CLASS__ . " 'saveSnapshot' method call failed");

        }

    }

    public function saveSnapCheck($deviceName, JSnapResults $jSnapResults)
    {

        extract($this->configIO->getConfigData());

        $finalSnapContent = serialize($jSnapResults);

        if (file_put_contents(
                $SnapCheckSwapPath . "/" .
                $this->generateCurrentKey($deviceName), $finalSnapContent) !== false) {

            return $this->generateCurrentKey($deviceName);

        }
        else {

            throw new \Exception(__CLASS__ . " 'saveSnapCheck' method call failed");

        }

    }

    public function loadSnapshot($deviceName, $key)
    {

        extract($this->configIO->getConfigData());

        $loadSnapContent = file_get_contents($SnapshotSwapPath . "/" . $this->formatKey($deviceName, $key));

        $snapSections = unserialize($loadSnapContent);

        $jSnapBundle = new JSnapSnapSectionBundle($key);

        foreach ($snapSections as $section) {

            $jSnapBundle->addSnapshotSection(
                new JSnapSnapSectionXML(
                    $section->getSnapType(),
                    $key,
                    $section->getSnapData()
                )
            );

        }

        return $jSnapBundle;

    }

    public function loadSnapCheck($deviceName, $jSnapKey)
    {

        extract($this->configIO->getConfigData());

        $loadSnapContent = file_get_contents($SnapCheckSwapPath . "/" . $this->formatKey($deviceName, $jSnapKey));

        $snapResults = unserialize($loadSnapContent);

        $jSnapBundle = [];

        $jSnapBundle ['failedTests'] = new JSnapSnapSectionBundle($jSnapKey);


        foreach ($snapResults->getFailedTests() as $sectionName => $sectionData) {

            $jSnapBundle['failedTests']->addSnapshotSection(
                new JSnapSnapSectionXML(
                    $sectionName,
                    $jSnapKey,
                    $sectionData
                )
            );

        }

        return $jSnapBundle;

    }

    public function loadSnapshotList($deviceName)
    {

        $swapPath = $this->configIO->getConfigDataParameter('SnapshotSwapPath');

        $swapDates = array();

        foreach (glob("{$swapPath}/{$deviceName}_*") as $swapFilename) {

            $swapFilename = basename($swapFilename);

            $swapFilename = $this->splitKey($swapFilename);

            $swapDates[$swapFilename[0]][] = $swapFilename[1];

        }

        if (count($swapDates) <= 0) {

            JSnapHelpers::JSONOutput("No snapshots found", 1);

        }

        return $swapDates;

    }

    public function deleteSnapshot($deviceName, $jSnapKey)
    {

        $swapPath = $this->configIO->getConfigDataParameter('SnapshotSwapPath');

        if (unlink("{$swapPath}/".$this->formatKey($deviceName, $jSnapKey)))
        {
            JSnapHelpers::JSONOutput("Snapshot deleted", 0);
        }
        else {
            JSnapHelpers::JSONOutput("Unable to delete snapshot", 1);
        }

    }

}