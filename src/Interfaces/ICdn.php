<?php

namespace FelixL7\Cdn\Interfaces;

interface ICdn
{
    public function getCSSFilePathWithoutExtension() : string;

    public function getJsFilePathWithoutExtension() : string;
}
