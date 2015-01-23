<?php namespace Lamoni\JSnapCommander\JSnapSnapSectionBundle;


class JSnapSnapSectionBundle
{
    protected $sections;
    protected $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function getSnapshotKey()
    {
        return $this->key;
    }

    public function getSnapshotSections()
    {
        return $this->sections;
    }

    public function addSnapshotSection($section)
    {
        $this->sections[] = $section;
    }
}