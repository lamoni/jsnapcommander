<?php namespace Lamoni\JSnapCommander\JSnapTriggerDriver;

use Lamoni\JSnapCommander\JSnapResults\JSnapResults;
use Lamoni\JSnapCommander\JSnapSnapSectionBundle\JSnapSnapSection\JSnapSnapSectionXML;
use Lamoni\JSnapCommander\JSnapHelpers\JSnapHelpers;
use Lamoni\JSnapCommander\JSnapSnapSectionBundle\JSnapSnapSectionBundle;

class JSnapTriggerDriverShell extends JSnapTriggerDriverAbstract
{

    public function snap($deviceName)
    {

        try {

            extract($this->configIO->getConfigData());

            chdir($SwapPath);

            $snapCmdOutput = shell_exec(escapeshellcmd('PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:' .
                    $JuiseExecutablePath . ':' . $JSnapExecutablePath . ' ' .
                    $JSnapExecutable . ' --snap ' . $jSnapTime .
                    ' -l ' . escapeshellarg($DeviceUsername) .
                    ' -p ' . escapeshellarg($DevicePassword) .
                    ' -t ' . escapeshellarg($deviceName) . ' ' .
                    $ConfigFile) . " 2>&1");

            /*
             * Validate jSnap ran correctly
             */
            if (strpos($snapCmdOutput, "Unable to connect to device") !== false) {

                throw new \Exception("Unable to connect to device");

            }
            elseif (strpos($snapCmdOutput, "The authenticity of host") !== false) {

                throw new \Exception("Host key not known for device");

            }

            $countFiles = 0;

            $jSnapSnapshotResults = array();

            foreach (glob($deviceName . "__*__" . $jSnapTime . ".xml") as $filename) {

                $countFiles++;

                $xml = file_get_contents($filename, FILE_TEXT);

                $check_type = explode("__", $filename);

                $check_type = $check_type[1];

                $jSnapSnapshotResults[] = new JSnapSnapSectionXML($check_type, $jSnapTime, $xml);

                unlink($filename);

            }

            if ($countFiles <= 0) {

                throw new \Exception("JSnap failed to run");

            }

            return $jSnapSnapshotResults;

        } catch (\Exception $e) {

            JSnapHelpers::JSONOutput($e->getMessage(), 1);

        }

    }

    public function check($deviceName, JSnapSnapSectionBundle $preSnap, JSnapSnapSectionBundle $postSnap)
    {

        try {

            extract($this->configIO->getConfigData());

            chdir($SwapPath);

            $snapshots = array($preSnap->getSnapshotSections(), $postSnap->getSnapshotSections());

            $snapTimes = array();

            foreach ($snapshots as $key => $snap) {

                foreach ($snap as $section) {

                    $fileNames[] = "{$deviceName}__{$section->getSnapType()}__{$section->getSnapTime()}.xml";

                    file_put_contents(end($fileNames), $section->getSnapData());

                    $snapTimes[$key] = $section->getSnapTime();

                }

            }

            $output = shell_exec(escapeshellcmd(
                    "PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:".
                    $JuiseExecutablePath . ":" . $JSnapExecutablePath . " " .
                    $JSnapExecutable . " --check {$snapTimes[0]},{$snapTimes[1]}" .
                    " -t ".escapeshellarg($deviceName) . " " . $ConfigFile) . " 2>&1");



            foreach ($fileNames as $filename) {

                unlink($filename);

            }

            $output = implode("\n", array_slice(explode("\n", $output), 4));

            if (strpos($output, "CHECKING SECTION: ") === false) {

                throw new \Exception("jSnap failed to run, please ensure you can run it from the shell");

            }

            $jSnapResults = new JSnapResults($deviceName);

            foreach (explode("CHECKING SECTION: ", $output) as $key => $section) {

                if ($key == 0) continue;

                $sectionSplit =
                    explode("\n---------------------------------------------------------------------------\n", $section);

                $sectionSplit = array_filter($sectionSplit);

                $testName = $sectionSplit[0];

                $testContents = $sectionSplit[1];

                $testInfo = array_filter(
                    preg_split("/[+-] TEST /", $testContents)
                );

                foreach ($testInfo as $info) {

                    if (substr($info, 0, 8) === "FAILED: ") {

                        list($infoName, $infoContent) = explode("\n", $info, 2);

                        $infoName = substr($infoName, 8);

                        $jSnapResults->addFailedTest($testName, $infoName, $infoContent);


                    }
                    else {

                        $infoContent = substr($info, 8);

                        $jSnapResults->addPassedTest($testName, $infoContent);

                    }
                }

            }

            return $jSnapResults;

        } catch (\Exception $e) {

            JSnapHelpers::JSONOutput($e->getMessage(), 1);

        }

    }

}