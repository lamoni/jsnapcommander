<?php namespace JSnapCommander\JSnapTriggerDriver;

use JSnapCommander\JSnapResults\JSnapResults;
use JSnapCommander\JSnapSnapSectionBundle\JSnapSnapSection\JSnapSnapSectionXML;
use JSnapCommander\JSnapHelpers\JSnapHelpers;
use JSnapCommander\JSnapSnapSectionBundle\JSnapSnapSectionBundle;

class JSnapTriggerDriverShell extends JSnapTriggerDriverAbstract
{

    public function snap($deviceName)
    {

        $deviceName = str_replace(array(';', '<', '>'), '', $deviceName);

        try {

            extract($this->configIO->getConfigData());

            chdir($SwapPath);

            $snapCmdOutput = shell_exec('PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:' .
                $JuiseExecutablePath . ':' . $JSnapExecutablePath . ' ' .
                $JSnapExecutable . ' --snap ' . $jSnapTime .
                ' -l ' . $DeviceUsername .
                ' -p ' . $DevicePassword .
                ' -t ' . $deviceName . ' ' .
                $ConfigFile . " 2>&1");

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

        $deviceName = str_replace(array(';', '<', '>'), '', $deviceName);

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

            $output = shell_exec(
                "PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:".
                "{$JuiseExecutablePath}:{$JSnapExecutablePath} " .
                "{$JSnapExecutable} --check {$snapTimes[0]},{$snapTimes[1]}" .
                " -t {$deviceName} {$ConfigFile} 2>&1"
            );

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

                if (strpos($section, "TEST FAILED") === false) {

                    $tests = array_filter(explode("+ TEST PASSED: ", $sectionSplit[1]));

                    foreach ($tests as $test) {

                        $jSnapResults->addPassedTest($testName, $test);

                    }

                }
                else {

                    $tests = array_filter(explode("- TEST FAILED: ", $sectionSplit[1]));
                    foreach ($tests as $test) {

                        $jSnapResults->addFailedTest($testName, $test);

                    }

                }

            }

            return $jSnapResults;

        } catch (\Exception $e) {

            JSnapHelpers::JSONOutput($e->getMessage(), 1);

        }

    }

}