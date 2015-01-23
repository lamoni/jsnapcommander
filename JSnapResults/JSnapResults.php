<?php namespace Lamoni\JSnapCommander\JSnapResults;

class JSnapResults
{

    protected $deviceName;

    protected $passedTests = array();

    protected $failedTests = array();

    public function __construct($deviceName)
    {

        $this->deviceName = $deviceName;

    }

    public function getDeviceName()
    {

        return $this->deviceName;

    }

    public function addPassedTest($testName, $passedTest)
    {

        $this->passedTests[$testName][] = $passedTest;

    }

    public function addFailedTest($testName, $failedTest)
    {

        $this->failedTests[$testName][] = $failedTest;

    }

    public function getTests()
    {

        return array_merge($this->passedTests, $this->failedTests);

    }

    public function getPassedTests()
    {

        return $this->passedTests;

    }

    public function getFailedTests()
    {

        return $this->failedTests;

    }

    public function toArray()
    {

        return array(
            'deviceName' => $this->getDeviceName(),
            'passedTests' => $this->getPassedTests(),
            'failedTests' => $this->getFailedTests()
        );

    }

}