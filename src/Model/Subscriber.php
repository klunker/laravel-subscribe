<?php

namespace Klunker\LaravelSubscribe\Model;

use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Klunker\LaravelSubscribe\Enums\SubscribeType;

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
        'subscribe_on' => AsEnumCollection::class . ':' . SubscribeType::class,
    ];


    public function getTable(): string
    {
        return config('subscribe.table_name', 'subscribers');
    }


    /**
     * Check if the subscriber is subscribed to a specific type.
     *
     * @param SubscribeType $type
     * @return bool
     */
    public function isSubscribedTo(SubscribeType $type): bool
    {
        return $this->subscribe_on && $this->subscribe_on->contains($type);
    }
}