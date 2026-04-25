<?php

namespace App\Enums;

enum MarketType: string
{
    case Duopoly = 'duopoly';
    case MonopolisticCompetition = 'monopolistic_competition';
    case PerfectCompetition = 'perfect_competition';

    public function label(): string
    {
        return match ($this) {
            self::Duopoly => 'Duopoly',
            self::MonopolisticCompetition => 'Monopolistic Competition',
            self::PerfectCompetition => 'Perfect Competition',
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
