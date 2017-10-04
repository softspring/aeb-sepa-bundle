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
}