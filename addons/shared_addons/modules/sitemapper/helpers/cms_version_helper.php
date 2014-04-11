<?php
if (!function_exists('cms_version')) {
    /**
     * @param $version
     * @param int $decimals
     * @return float
     */
    function cms_version($version, $decimals = 2)
    {
        // Put the different parts of the version in an array
        $version_array = strpos_find($version, '.', $decimals);
        // Return as float to be used with comparison (ints)
        return (float)$version_array[0];
    }
}

if (!function_exists('strpos_find')) {
    /**
     * @param $string
     * @param $needle
     * @param int $nth
     * @return array
     */
    function strpos_find($string, $needle, $nth = 1)
    {
        $max = strlen($string);
        $n = 0;
        for ($i = 0; $i < $max; $i++) {
            if ($string[$i] == $needle) {
                $n++;
                if ($n >= $nth) {
                    break;
                }
            }
        }
        $arr[] = substr($string, 0, $i);
        $arr[] = substr($string, $i + 1, $max);
        return $arr;
    }
}
