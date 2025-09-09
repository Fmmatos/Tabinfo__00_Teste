<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Vendor\Models\ItemsCategories>
 */
class ItemsCategoryFactory
{
    protected $model = \Vendor\Models\ItemsCategories::class;
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
            'subcategories' => $this->faker->optional(0.8)->randomElement(DB::table('items_categories')->pluck('id')->toArray()),
            'order' => $this->faker->numberBetween(1, 999),
        ];
    }
}
