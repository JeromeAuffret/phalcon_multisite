<?php

namespace Libraries;

use DateTime;


final class Helpers
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
     * @param        $date
     * @param string $input_format
     * @param string $output_format
     *
     * @return string
     */
    public static function date2sql($date, $output_format = 'Y-m-d', $input_format = 'd/m/Y') {
        return self::formatDate($date, $output_format, $input_format);
    }

    /**
     * @param        $date
     * @param string $input_format
     * @param string $output_format
     *
     * @return string
     */
    public static function date2txt($date, $output_format = 'd/m/Y', $input_format = 'Y-m-d') {
        return self::formatDate($date, $output_format, $input_format) ?: '';
    }

    /**
     * @param        $datetime
     * @param string $output_format
     * @param string $input_format
     *
     * @return string
     */
    public static function datetime2sql($datetime, $output_format = 'Y-m-d H:i:s', $input_format = 'd/m/Y H:i:s') {
        return self::formatDate($datetime, $output_format, $input_format);
    }

    /**
     * @param        $datetime
     * @param string $output_format
     * @param string $input_format
     *
     * @return string
     */
    public static function datetime2txt($datetime, $output_format = "d/m/Y H:i", $input_format = "Y-m-d H:i:s") {
        return self::formatDate($datetime, $output_format, $input_format) ?: '';
    }

    /**
     * @param        $datetime
     * @param string $output_format
     * @param string $input_format
     *
     * @return string
     */
    public static function datetime2txtdate($datetime, $output_format = "d/m/Y", $input_format = "Y-m-d H:i:s") {
        return self::formatDate($datetime, $output_format, $input_format) ?: '';
    }

    /**
     * @param        $datetime
     * @param string $output_format
     * @param string $input_format
     *
     * @return string
     */
    public static function datetime2txttime($datetime, $output_format = "H:i:s", $input_format = "Y-m-d H:i:s") {
        return self::formatDate($datetime, $output_format, $input_format) ?: '';
    }

}
