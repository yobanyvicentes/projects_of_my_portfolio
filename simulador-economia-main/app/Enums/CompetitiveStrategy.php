<?php

namespace App\Enums;

enum CompetitiveStrategy: string
{
    case PriceCompetition = 'price_competition';
    case AdvertisingCompetition = 'advertising_competition';
    case Balanced = 'balanced';

    public function label(): string
    {
        return match ($this) {
            self::PriceCompetition => 'Price Competition',
            self::AdvertisingCompetition => 'Advertising Competition',
            self::Balanced => 'Balanced Strategy',
        };
    }

    public static function options(): array
    {
        return array_reduce(self::cases(), function (array $carry, self $case) {
            $carry[$case->value] = $case->label();
            return $carry;
        }, []);
    }
}
