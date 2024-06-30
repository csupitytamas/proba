<?php

namespace App\Helpers;

class Request
{
    /**
     * Calculates the GET parameters for a given URL array and parameters object.
     *
     * @param string $urlArray The URL array to calculate the GET parameters for.
     * @param object &$parameters The parameters object to calculate the GET parameters for.
     *
     * @return void
     */
    public static function calculateGetParameters(string $urlArray, object &$parameters): void
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

    /**
     * Calculates the URL and parameters for a given URL array and parameters object.
     *
     * @param string &$urlArray The URL array to calculate the URL and parameters for.
     * @param object &$parameters The parameters object to calculate the URL and parameters for.
     *
     * @return void
     */
    public static function calculateUrlAndParameters(string &$urlArray, object &$parameters): void
    {
        Request::calculateGetParameters($urlArray, $parameters);
        if (str_contains($urlArray, "?")) {
            $urlArray = strstr($urlArray, '?', true);
        }
    }
}