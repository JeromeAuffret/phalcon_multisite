<?php

namespace Libraries;

use DateTime;


final class DateHelper
{
    /**
     * @param string $date
     * @param string $output_format
     * @param string $input_format
     *
     * @return string|null
     */
    public static function formatDate(string $date, string $output_format, $input_format = 'Y-m-d H:i:s')
    {
        if (!empty($date)) {
            $datetime = DateTime::createFromFormat($input_format, $date);
            if ($datetime != false) {
                return $datetime->format($output_format);
            }
        }
        return null;
    }

    /**
     * @param string $date
     * @param string $input_format
     * @param string $output_format
     *
     * @return string
     */
    public static function date2sql(string $date, $output_format = 'Y-m-d', $input_format = 'd/m/Y') {
        return self::formatDate($date, $output_format, $input_format);
    }

    /**
     * @param string $date
     * @param string $input_format
     * @param string $output_format
     *
     * @return string
     */
    public static function date2txt(string $date, $output_format = 'd/m/Y', $input_format = 'Y-m-d') {
        return self::formatDate($date, $output_format, $input_format);
    }

    /**
     * @param string $datetime
     * @param string $output_format
     * @param string $input_format
     *
     * @return string
     */
    public static function datetime2sql(string $datetime, $output_format = 'Y-m-d H:i:s', $input_format = 'd/m/Y H:i:s') {
        return self::formatDate($datetime, $output_format, $input_format);
    }

    /**
     * @param string $datetime
     * @param string $output_format
     * @param string $input_format
     *
     * @return string
     */
    public static function datetime2txt(string $datetime, $output_format = "d/m/Y H:i", $input_format = "Y-m-d H:i:s") {
        return self::formatDate($datetime, $output_format, $input_format);
    }

    /**
     * @param string $datetime
     * @param string $output_format
     * @param string $input_format
     *
     * @return string
     */
    public static function datetime2date(string $datetime, $output_format = "d/m/Y", $input_format = "Y-m-d H:i:s") {
        return self::formatDate($datetime, $output_format, $input_format);
    }

    /**
     * @param string $datetime
     * @param string $output_format
     * @param string $input_format
     *
     * @return string
     */
    public static function datetime2time(string $datetime, $output_format = "H:i:s", $input_format = "Y-m-d H:i:s") {
        return self::formatDate($datetime, $output_format, $input_format);
    }

}
