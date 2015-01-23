<?php namespace Lamoni\JSnapCommander\JSnapSnapSectionBundle\JSnapSnapSection;

abstract class JSnapSnapSectionAbstract
{
    protected $snapType;

    protected $snapTime;

    protected $snapData;

    public function __construct($snapType, $snapTime, $snapData)
    {

        $this->snapType = $snapType;

        $this->snapTime = $snapTime;

        $this->snapData = $snapData;

    }

    /**
     * @return string
     */
    public function getSnapType()
    {
        return (string)$this->snapType;
    }

    /**
     * @return string
     */
    public function getSnapTime()
    {
        return (string)$this->snapTime;
    }

    /**
     * @return string
     */
    public function getSnapData()
    {
        return (string)$this->snapData;
    }



}