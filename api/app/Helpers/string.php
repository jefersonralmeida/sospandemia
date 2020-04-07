<?php

if (!function_exists('removeAccents')) {

    function removeAccents(string $string): string
    {
        $patterns = [
            "/á|à|ã|â|ä/",
            "/Á|À|Ã|Â|Ä/",
            "/é|è|ê|ë/",
            "/É|È|Ê|Ë/",
            "/í|ì|î|ï/",
            "/Í|Ì|Î|Ï/",
            "/ó|ò|õ|ô|ö/",
            "/Ó|Ò|Õ|Ô|Ö/",
            "/ú|ù|û|ü/",
            "/Ú|Ù|Û|Ü/",
            "/ñ/",
            "/Ñ/",
            "/ç/",
            "/Ç/"
        ];
        $replacements = 'aAeEiIoOuUnNcC';
        return preg_replace($patterns, str_split($replacements), $string);
    }

    function removeSpecialChars(string $string): string
    {
        return preg_replace('/[^A-Za-z0-9]/', ' ', $string);
    }

    function removeDuplicatedSpaces(string $string): string
    {
        return preg_replace('/\s+/', ' ', $string);
    }

    function sanitizeSearch(string $query): string
    {
        $query = removeAccents($query);
        $query = removeSpecialChars($query);
        $query = removeDuplicatedSpaces($query);
        return $query;
    }
}
