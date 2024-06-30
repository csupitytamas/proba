<?php

namespace App\Helpers;

class Request
{
    public static function calculateGetParameters(string &$urlArray, object &$parameters): void
    {
        if (str_contains($urlArray, '?')) {
            $getParametersList = substr($urlArray, strpos($urlArray, "?") + 1);
            $getParameters = explode('&', $getParametersList);
            foreach ($getParameters as $parameter) {
                [$key, $value] = explode('=', $parameter);
                $parameters->{$key} = $value;
            }
        }
    }
}