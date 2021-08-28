<?php

namespace FelixL7\Resource\CDNs;

abstract class AbstractCdn
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
        $urlParts = [$this->getDomain(), $this->getLibPath(), $this->libPathName, $this->version, $filePath, $this->fileName];
        $urlString = "";

        foreach ($urlParts as $key => $urlPart) {
            $part = trim($urlPart, "/");

            if(empty($part)) {
                continue;
            }

            $urlString .= $this->getFilePathPart($part, $key);
        }

        return $urlString;
    }

    protected function getFilePathPart($part, $key) {
        switch ($key) {
            case 0:
                return $part;

            default:
                return "/{$part}";
        }
    }

    public abstract function getDomain() : string;

    protected abstract function getLibPath() : string;
}
