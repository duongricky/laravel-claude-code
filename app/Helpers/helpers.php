<?php

if (! function_exists('format_date')) {
    function format_date(mixed $date, string $format = 'd/m/Y'): string
    {
        if (! $date) {
            return '';
        }

        return \Carbon\Carbon::parse($date)->format($format);
    }
}

if (! function_exists('currency')) {
    function currency(int|float $amount, string $symbol = '₫'): string
    {
        return number_format($amount, 0, ',', '.') . ' ' . $symbol;
    }
}
