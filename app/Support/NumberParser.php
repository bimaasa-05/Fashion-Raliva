<?php

namespace App\Support;

class NumberParser
{
    public static function integerInput(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        $text = trim($value);
        if ($text === '' || ! preg_match('/^-?\d[\d.]*$/', $text)) {
            return $value;
        }

        return str_replace('.', '', $text);
    }

    public static function decimalInput(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        $text = trim(str_replace(' ', '', $value));
        if ($text === '' || ! preg_match('/^-?[\d.,]+$/', $text)) {
            return $value;
        }

        $normalized = str_replace('.', '', $text);
        $normalized = str_replace(',', '.', $normalized);

        return is_numeric($normalized) ? $normalized : $value;
    }
}
