<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ColfuturoProfile extends Model
{
    protected $fillable = [
        'promotion_year',
        'name',
        'gender',
        'department',
        'undergraduate_university',
        'undergraduate_program',
        'postgraduate_university',
        'country',
        'destination_city',
        'postgraduate_type',
        'postgraduate_program',
        'area',
        'status',
        'search_vector',
    ];

    protected $casts = [
        'promotion_year' => 'integer',
    ];

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['gender'] ?? null, fn (Builder $builder, string $value) => $builder->where('gender', $value))
            ->when($filters['department'] ?? null, fn (Builder $builder, string $value) => $builder->where('department', $value))
            ->when($filters['undergraduate_university'] ?? null, fn (Builder $builder, string $value) => $builder->where('undergraduate_university', $value))
            ->when($filters['undergraduate_program'] ?? null, fn (Builder $builder, string $value) => $builder->where('undergraduate_program', $value))
            ->when($filters['postgraduate_university'] ?? null, fn (Builder $builder, string $value) => $builder->where('postgraduate_university', $value))
            ->when($filters['country'] ?? null, fn (Builder $builder, string $value) => $builder->where('country', $value))
            ->when($filters['destination_city'] ?? null, fn (Builder $builder, string $value) => $builder->where('destination_city', $value))
            ->when($filters['postgraduate_type'] ?? null, fn (Builder $builder, string $value) => $builder->where('postgraduate_type', $value))
            ->when($filters['postgraduate_program'] ?? null, fn (Builder $builder, string $value) => $builder->where('postgraduate_program', $value))
            ->when($filters['area'] ?? null, fn (Builder $builder, string $value) => $builder->where('area', $value))
            ->when($filters['status'] ?? null, fn (Builder $builder, string $value) => $builder->where('status', $value))
            ->when(
                isset($filters['promotion_year_min']) && (int) $filters['promotion_year_min'] > 0,
                function (Builder $builder) use ($filters) {
                    $builder
                        ->whereNotNull('promotion_year')
                        ->where('promotion_year', '>=', (int) $filters['promotion_year_min']);
                }
            )
            ->when(
                isset($filters['promotion_year_max']) && (int) $filters['promotion_year_max'] > 0,
                function (Builder $builder) use ($filters) {
                    $builder
                        ->whereNotNull('promotion_year')
                        ->where('promotion_year', '<=', (int) $filters['promotion_year_max']);
                }
            )
            ->when($filters['search'] ?? null, function (Builder $builder, string $value) {
                $term = '%' . trim($value) . '%';

                $builder->where(function (Builder $query) use ($term) {
                    $query
                        ->where('name', 'like', $term)
                        ->orWhere('undergraduate_program', 'like', $term)
                        ->orWhere('undergraduate_university', 'like', $term)
                        ->orWhere('postgraduate_program', 'like', $term)
                        ->orWhere('postgraduate_university', 'like', $term)
                        ->orWhere('country', 'like', $term)
                        ->orWhere('destination_city', 'like', $term)
                        ->orWhere('area', 'like', $term)
                        ->orWhere('search_vector', 'like', $term);
                });
            });
    }
}
