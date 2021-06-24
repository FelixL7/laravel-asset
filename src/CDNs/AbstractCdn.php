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

    public function getFilePathWithoutExtension($filePath = "/") : string {
        return "{$this->getDomain()}{$this->getLibPath()}/{$this->libPathName}@{$this->version}{$filePath}/{$this->fileName}";
    }

    public abstract function getDomain() : string;

    protected abstract function getLibPath() : string;
}
