<?php 

namespace JPF;

use JPF\Logger\Logger;
class App
{
    public static function run()
    {
        Logger::enableSystemLogs();
    }
}