<?php
/**
 * GettersSettersInitialization
 */
namespace application\core;
class GettersSettersInitialization
{

    public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new GettersSetters();
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