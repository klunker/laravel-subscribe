<?php

namespace Klunker\LaravelSubscribe\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Klunker\LaravelSubscribe\Enums\SubscribeType;
use Klunker\LaravelSubscribe\Model\Subscriber;

class SubscriberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subscriber::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $subscriptionTypes = array_values(SubscribeType::cases());
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'name' => $this->faker->name(),
            'subscribe_on' => $this->faker->randomElements(
                $subscriptionTypes,
                rand(1, count($subscriptionTypes))
            ),
        ];
    }
}
