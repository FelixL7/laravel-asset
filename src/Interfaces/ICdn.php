<?php

namespace FelixL7\Cdn\Interfaces;

interface ICdn
{
    public function getFilePathWithoutExtension($filePath = "/") : string;
    public function getDomain() : string;
}
