<?php

namespace App\Services;

class FarsiLanguageToFontImplementation extends \Mpdf\Language\LanguageToFont
{
    public function getLanguageOptions($llcc, $adobeCJK)
    {

        if ($llcc === 'utf-8') {
            return [false, 'iranyekan'];
        }

        return parent::getLanguageOptions($llcc, $adobeCJK);
    }

}
