<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Root\Models\CustomersCategories>
 */
class CustomersCategoryFactory
{
    protected $model = \Root\Models\CustomersCategories::class;
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
            'type' => $this->faker->numberBetween(1, 100),
            'subcategories' => $this->faker->numberBetween(1, 100),
            'order' => $this->faker->numberBetween(1, 999),
            'weight' => $this->faker->randomFloat(2, 0, 1000),
            'limit' => $this->faker->optional(0.8)->numberBetween(1, 100),
            'range' => $this->faker->optional(0.8)->paragraph(5),
            'modality' => $this->faker->optional(0.8)->paragraph(5),
            'gloves' => $this->faker->optional(0.8)->paragraph(5),
        ];
    }
}
