<?php

namespace Mithra62\Tel\Services;

class FormatService
{
    public function phone(string $data): string
    {
        $phone_number = $data;
        $phone_number = htmlspecialchars($phone_number);
        $phone_number = preg_replace('[\D]', '', $phone_number);
        $phone_number = preg_replace('/[^0-9]/', '', $phone_number);

        if (strlen($phone_number) > 10) {
            $country_code = substr($phone_number, 0, strlen($phone_number) - 10);
            $area_code = substr($phone_number, -10, 3);
            $next_three = substr($phone_number, -7, 3);
            $last_four = substr($phone_number, -4, 4);

            $phone_number = '+' . $country_code . ' (' . $area_code . ') ' . $next_three . '-' . $last_four;
        } elseif (strlen($phone_number) == 10) {
            $area_code = substr($phone_number, 0, 3);
            $next_three = substr($phone_number, 3, 3);
            $last_four = substr($phone_number, 6, 4);

            $phone_number = '(' . $area_code . ') ' . $next_three . '-' . $last_four;
        } elseif (strlen($phone_number) == 7) {
            $next_three = substr($phone_number, 0, 3);
            $last_four = substr($phone_number, 3, 4);

            $phone_number = $next_three . '-' . $last_four;
        }

        return $phone_number;
    }
}