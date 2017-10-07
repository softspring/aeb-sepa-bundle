<?php

namespace Softspring\AebSepaBundle\Utils;

class Aeb1914
{
    /**
     * @param string $presenterId
     *
     * @return string
     */
    public static function presentationFileId($presenterId)
    {
        return 'PRE'.date('YmdHis')
            .str_pad(date('v'), 5 ,'0', STR_PAD_LEFT)
            .str_pad($presenterId, 13 ,'0', STR_PAD_LEFT);
    }

    /**
     * @param string $country       ISO-3166
     * @param string $suffix        3 digits
     * @param string $creditorId    National Identification (NIF, NIE, ...)
     *
     * @return string
     */
    public static function creditorId($country, $suffix, $creditorId)
    {
        $cleanCreditorId = str_replace(['/', '-', '?', ':', '(', ')', '.', ',', "'", '+', ' '], '', $creditorId);

        $controlDigitsString = $cleanCreditorId.$country.'00';

        preg_match_all('/[A-Z]/', $controlDigitsString, $matches);
        foreach (array_unique($matches[0]) as $letter) {
            $controlDigitsString = str_replace($letter, (ord($letter) - 55), $controlDigitsString);
        }

        $controlDigits = 98 - bcmod($controlDigitsString, '97');

        return $country.($controlDigits<10?'0':'').$controlDigits.$suffix.$cleanCreditorId;
    }
}