<?php

namespace Webkul\Core\Traits;

use App\Services\FarsiLanguageToFontImplementation;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

trait PDFHandler
{
    /**
     * Download PDF.
     *
     * @param  string  $html
     * @param  string  $fileName
     *
     * @return \Illuminate\Http\Response
     */
    protected function downloadPDF(string $html, ?string $fileName = null)
    {
        if (is_null($fileName)) {
            $fileName = Str::random(32);
        }

        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

        $document = new Mpdf([
            'mode'           => 'utf-8',
            'format'         => 'A4',
            'margin_header'  => '3',
            'margin_top'     => '20',
            'margin_bottom'  => '20',
            'margin_footer'  => '2',
            'tempDir'        => base_path().'/storage/mpdf/tmp',
            'languageToFont' => new FarsiLanguageToFontImplementation(),
            'fontDir'        => [
                base_path().'/public/fonts/IranYekan/ttf',
            ],
            'fontdata'       => [
                'iranyekan' => [
                    'R'      => 'iranyekanwebregularfanum.ttf',
                    'I'      => 'iranyekanwebregularfanum.ttf',
                    'useOTL' => 0xFF,
                ]
            ],
            'default_font'   => 'iranyekan',
            'direction'      => 'rtl'
        ]);
        $document->autoScriptToLang = true;
        $document->autoLangToFont = true;
        $document->SetDirectionality('rtl');
        $document->WriteHTML($html);
        return $document->Output($fileName.".pdf", Destination::DOWNLOAD);
    }

    /**
     * Adjust arabic and persian content.
     *
     * @param  string  $html
     *
     * @return string
     */
    protected function adjustArabicAndPersianContent(string $html)
    {
        $arabic = new \ArPHP\I18N\Arabic();

        $p = $arabic->arIdentify($html);

        for ($i = count($p) - 1; $i >= 0; $i -= 2) {
            $utf8ar = $arabic->utf8Glyphs(substr($html, $p[$i - 1], $p[$i] - $p[$i - 1]));
            $html = substr_replace($html, $utf8ar, $p[$i - 1], $p[$i] - $p[$i - 1]);
        }

        return $html;
    }
}
