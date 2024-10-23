<?php
declare(strict_types=1);
namespace Marcus\Zenitech\Providers;

abstract class Enviroment
{
    public function __construct()
    {
    }

    public static function addEnv()
    {
        $path = __DIR__ . '/../../.env';
        $fileEnv = file_exists($path) ? file($path) : false;
        if (!$fileEnv) {
            return false;
        }
        foreach ($fileEnv as $line) {
            putenv(trim($line));
        }
        
    }
}