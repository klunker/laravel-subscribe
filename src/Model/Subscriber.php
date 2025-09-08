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

    public function getTable(): string
    {
        return config('subscribe.table_name', 'subscribers');
    }
}