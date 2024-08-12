<?php

if(!function_exists('env')) {
    function env($key, $default = null)
    {
        return isset($_ENV[$key]) ? $_ENV[$key] : $default;
    }
}