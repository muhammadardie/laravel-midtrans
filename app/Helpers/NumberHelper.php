<?php
namespace App\Helpers;

class NumberHelper
{
	public static function getRandomNumber($length = 8): int
    {
        $intMin = (10 ** $length) / 10;
        $intMax = (10 ** $length) - 1;

        try {
            $randomNumber = random_int($intMin, $intMax);
        } catch (\Exception $exception) {
            \Log::error('Failed to generate random number Retrying...');
            \Log::debug(' Error: '.$exception->getMessage());
            $randomNumber = self::getRandomNumber($length);
        }
        return $randomNumber;
    }
}