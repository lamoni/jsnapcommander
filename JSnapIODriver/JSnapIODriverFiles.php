<?php namespace JSnapCommander\JSnapIODriver;

use JSnapCommander\JSnapConfig\JSnapConfigAbstract;
use JSnapCommander\JSnapHelpers\JSnapHelpers;
use JSnapCommander\JSnapSnapSectionBundle\JSnapSnapSectionBundle;
use JSnapCommander\JSnapSnapSectionBundle\JSnapSnapSection\JSnapSnapSectionXML;

class JSnapIODriverFiles extends JSnapIODriverAbstract
{

    public function save($deviceName, array $jSnapResults)
    {
        extract($this->configIO->getConfigData());

        $finalSnapContent = "";
        foreach ($jSnapResults as $jResult) {
            $finalSnapContent .= $jResult->getSnapType() . "++----------++" . $jResult->getSnapData() . "@===============@";
        }

        $finalSnapContent = rtrim($finalSnapContent, "@===============@");
        if (file_put_contents($SwapPath . "/" . $this->generateCurrentKey($deviceName), $finalSnapContent) !== false) {
            return $this->generateCurrentKey($deviceName);
        } else {
            throw new \Exception(__CLASS__ . " 'save' method call failed");
        }

    }

    public function load($deviceName, $key)
    {

        extract($this->configIO->getConfigData());

        $loadSnapContent = file_get_contents($SwapPath . "/" . $this->formatKey($deviceName, $key));
        $snapSections = explode("@===============@", $loadSnapContent);
        $jSnapBundle = new JSnapSnapSectionBundle($key);
        foreach ($snapSections as $section) {

            $section = explode("++----------++", $section);
            $section_name = $section[0];
            $section_xml = $section[1];
            $jSnapBundle->addSnapshotSection(new JSnapSnapSectionXML($section_name, $key, $section_xml));

        }

        return $jSnapBundle;

    }

    public function loadList($deviceName)
    {
        $swapPath = $this->configIO->getConfigDataParameter('SwapPath');

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



}