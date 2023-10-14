<?php

/**
 * Base service class should contain methods and attributes
 * shared with all others services
 */

namespace App\Services;

use DateTime;

abstract class BaseService
{
    /**
     * Validate a date format yyyymmdd
     *
     * @param string $date
     *
     * @return boolean
     */
    public function validateDateFormat(string $date, string $format = 'Ymd')
    {
        $datetime = DateTime::createFromFormat($format, $date);
        return $datetime && $datetime->format($format) === $date;
    }
}
