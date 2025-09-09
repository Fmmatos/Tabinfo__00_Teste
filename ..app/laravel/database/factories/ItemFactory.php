<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Vendor\Models\Items>
 */
class ItemFactory
{
    protected $model = \Vendor\Models\Items::class;
    public $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create('pt_BR');
    }

    public function definition()
    {
        return [
            'active' => $this->faker->boolean(80),
            'name' => $this->faker->name(),
            'image' => $this->faker->optional(0.8)->paragraph(5),
            'type' => $this->faker->lexify('????????????????????'),
            'categories' => $this->faker->optional(0.8)->randomElement(DB::table('items_categories')->pluck('id')->toArray()),
            'order' => $this->faker->numberBetween(1, 999),
            'rel' => $this->faker->optional(0.8)->numberBetween(1, 100),
            'place' => $this->faker->numberBetween(1, 100),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'date' => $this->faker->optional(0.8)->dateTimeBetween('-2 years', 'now') ? $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d') : null,
            'description' => $this->faker->optional(0.8)->paragraph(3),
        ];
    }
}
