<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'iso2',
        'iso3',
        'numeric_code',
        'phonecode',
        'capital',
        'currency',
        'currency_name',
        'currency_symbol',
        'tld',
        'native',
        'region',
        'subregion',
        'timezones',
        'translations',
        'latitude',
        'longitude',
        'emoji',
        'emojiU',
        'flag',
        'is_active',
    ];

    protected $casts = [
        'translations' => 'array',
        'timezones' => 'array',
        'flag' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    /**
     * Scope a query to only include active Countries.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', 1);
    }

    /**
     * get Country By iso2 code.
     *
     * @throws \Throwable
     */
    public static function getByIso2(string $iso2)
    {
        $country = static::where('iso2', strtoupper($iso2))->first();
        throw_if(is_null($country), "{$iso2} does not exist");

        return $country;
    }

    /**
     * get Country By iso3 code.
     *
     * @throws \Throwable
     */
    public static function getByIso3(string $iso3)
    {
        $country = static::where('iso3', strtoupper($iso3))->first();
        throw_if(is_null($country), "{$iso3} does not exist");

        return $country;
    }

    /**
     * @throws \Throwable
     */
    public static function getByCode(string $code)
    {
        $code = strtoupper($code);
        $columns = \mb_strlen($code) == 3 ? 'iso3' : 'iso2';
        $country = static::where($columns, $code)->first();
        throw_if(is_null($country), "{$code} does not exist");

        return $country;
    }
}
