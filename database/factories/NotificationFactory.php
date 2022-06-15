<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Notification;
use Bagoesz21\LaravelNotification\Enums\NotificationLevel;

class NotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Notification::class;

    protected $counter = 0;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $this->counter++;
        $timezone = config('app.timezone');

        return [
            'title' => $this->faker->text(25),
            'message' => $this->faker->text(250),

            'level' => NotificationLevel::INFO,

            'image' => null,
            // 'external_url' => $this->faker->url(),

            'data' => null,

            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now', $timezone),
            'updated_at' => $this->faker->dateTimeBetween('-20 days', 'now', $timezone),
            'deleted_at' => ($this->counter % 5 == 0) ? $this->faker->dateTimeBetween('-30 days', 'now', $timezone) : null
        ];
    }
}
