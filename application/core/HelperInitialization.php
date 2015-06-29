<?php
namespace application\core;
class HelperInitialization
{
    public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new HelperBigStep();
        }
        return $instance;
    }
    protected function __construct()
    {
    }
    private function __clone()
    {
    }
    private function __wakeup()
    {
    }
}