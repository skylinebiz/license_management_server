<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Singleton: one row for the whole application, shared across every
 * license. Always go through current() rather than querying directly.
 */
class LicenseProvider extends Model
{
    protected $fillable = [
        'name',
        'email',
    ];

    /**
     * The one provider record, creating an empty one if it doesn't exist yet.
     */
    public static function current(): self
    {
        return static::query()->firstOrCreate([]);
    }
}
