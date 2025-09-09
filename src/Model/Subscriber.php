<?php

namespace Klunker\LaravelSubscribe\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscriber extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'email',
        'name',
        'subscribe_on',
    ];

    protected $casts = [
        'email' => 'string',
        'name' => 'string',
        'subscribe_on' => 'json',
    ];


    public function getTable(): string
    {
        return config('subscribe.table_name', 'subscribers');
    }


    /**
     * Check if the subscriber is subscribed to a specific broadcast channel.
     *
     * @param string $type
     * @return bool
     */
    public function isSubscribedTo(string $type): bool
    {
        return in_array($type, $this->subscribe_on ?? []);
    }
}