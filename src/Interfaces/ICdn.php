<?php

namespace FelixL7\Resource\Interfaces;

interface ICdn
{
    public function getFilePathWithoutExtension($filePath = "/") : string;
    public function getDomain() : string;
}
