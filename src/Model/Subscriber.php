<?php

namespace Klunker\LaravelSubscribe\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscriber extends Model
{
    use softDeletes;

    protected $fillable = [
        'email',
        'name',
        'service',
        'marketing',
    ];

    protected $casts = [
        'email' => 'string',
        'name' => 'string',
        'service' => 'boolean',
        'marketing' => 'boolean',
    ];

    protected static function booted(): void
    {

        static::updating(function (Subscriber $subscriber) {
            if (
                $subscriber->isDirty(['service', 'marketing'])
                && $subscriber->service === false
                && $subscriber->marketing === false
            ) {
                $subscriber->delete();
                return false;
            }
            return true;
        });
    }

    public function getTable(): string
    {
        return config('subscribe.table_name', 'subscribers');
    }
}