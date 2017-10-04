<?php

namespace Softspring\AebSepaBundle\Utils;

class AebFormat
{
    private static $validChars = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 'S', 't', 'u', 'v', 'w', 'x', 'y', 'z',
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '/', '-', '?', ':', '(', ')', '.', ',', '‘', '+', ' ',
    ];

    /**
     * @param string $text
     *
     * @return string
     */
    public static function text($text)
    {
        $replacements = [
            'Ñ' => 'N',
            'ñ' => 'n',

            'Ç' => 'C',
            'ç' => 'c',

            'Á' => 'A',
            'É' => 'E',
            'Í' => 'I',
            'Ó' => 'O',
            'Ú' => 'U',
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',

            'À' => 'A',
            'È' => 'E',
            'Ì' => 'I',
            'Ò' => 'O',
            'Ù' => 'U',
            'à' => 'a',
            'è' => 'e',
            'ì' => 'i',
            'ò' => 'o',
            'ù' => 'u',

            'Ä' => 'A',
            'Ë' => 'E',
            'Ï' => 'I',
            'Ö' => 'O',
            'Ü' => 'U',
            'ä' => 'a',
            'ë' => 'e',
            'ï' => 'i',
            'ö' => 'o',
            'ü' => 'u',

            'Â' => 'A',
            'Ê' => 'E',
            'Î' => 'I',
            'Ô' => 'O',
            'Û' => 'U',
            'â' => 'a',
            'ê' => 'e',
            'î' => 'i',
            'ô' => 'o',
            'û' => 'u',
        ];

        $text = str_replace(array_keys($replacements), array_values($replacements), $text);

        return $text;
    }

    /**
     * @param \DateTime $dateTime
     *
     * @return string
     */
    public static function date(\DateTime  $dateTime)
    {
        return $dateTime->format('Ymd');
    }

    /**
     * @param float|int|string $number
     *
     * @return string
     */
    public static function float($number)
    {
        if (is_string($number)) {
            $number = floatval($number);
        } else if (is_int($number)) {
            $number = (float)$number;
        }

        return str_replace('.', '', sprintf('%0.2f', $number));
    }

    /**
     * @return string[]
     */
    public static function getValidChars()
    {
        return self::$validChars;
    }
}