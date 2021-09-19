<?php

namespace FelixL7\Asset\Interfaces;

interface ICdn
{
    public function getFilePathWithoutExtension($filePath = "/") : string;
    public function getDomain() : string;
}
