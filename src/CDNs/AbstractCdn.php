<?php

namespace FelixL7\Cdn\CDNs;

abstract class AbstractCDN
{
    protected $libPathName;
    protected $version;
    protected $fileName;

    public function __construct(string $libPathName, string $version, string $fileName)
    {
        $this->libPathName = $libPathName;
        $this->version = $version;
        $this->fileName = $fileName;
    }

    public abstract function getCSSFilePathWithoutExtension() : string;

    public abstract function getJsFilePathWithoutExtension() : string;

    public abstract function getFilePathWithoutExtension(array $args = []) : string;
}
