<?php

if (!function_exists('en_to_fa')) {
    /**
     * Convert english digits to farsi.
     *
     * @param  string  $text
     *
     * @return string
     */
    function en_to_fa($text)
    {
        $en_num = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $fa_num = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

        return str_replace($en_num, $fa_num, $text);
    }
}

if (!function_exists('fa_to_en')) {
    /**
     * Convert farsi/arabic digits to english.
     *
     * @param  string  $text
     *
     * @return string
     */
    function fa_to_en($text)
    {
        $fa_num = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $en_num = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($fa_num, $en_num, $text);
    }
}

if (!function_exists('str_to_slug')) {
    /**
     * Returns slug for string.
     *
     * @param  string  $string
     * @param  string  $separator
     *
     * @return string
     */
    function str_to_slug(string $string, string $separator = '-')
    {
        $string = fa_to_en(trim(mb_strtolower($string)));
        $string = preg_replace('!['.preg_quote('-' === $separator ? '_' : '-').']+!u', $separator, $string);

        return preg_replace(
            '/\\'.$separator.'{2,}/',
            $separator,
            preg_replace('/[^A-Za-z0-9\x{0620}-\x{064A}\x{0698}\x{067E}\x{0686}\x{06AF}\x{06CC}\x{06A9}]/ui',
                $separator, $string)
        );
    }
}

if (!function_exists('remove_arabic_characters')) {
    function remove_arabic_characters(string $value): string
    {
        return str_replace(
            ['ي', 'ك', 'كٔ', 'کٔ'],  // Added کٔ (Persian Kaf with hamza)
            ['ی', 'ک', 'ک', 'ک'],
            $value
        );
    }
}
