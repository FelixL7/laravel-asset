<?php

namespace FelixL7\Asset;

class Asset
{
    private $libs = [];


    public function __call($name, $arguments)
    {
        if(isset($this->libs[$name])) {
            $lib = $this->libs[$name];

            return new $lib;
        }

        return null;
    }

    public function register(array $libs) {
        foreach ($libs as $lib) {
            try {
                $name = (new $lib)->getName();
                $this->libs[$name] = $lib;
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

    public function empty() {
        $this->libs = [];
    }
}
