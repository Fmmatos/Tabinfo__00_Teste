<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Vendor\Models\YMenuAdminCategories>
 */
class YMenuAdminCategoryFactory
{
    protected $model = \Vendor\Models\YMenuAdminCategories::class;
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
            'title' => $this->faker->sentence(5),
        ];
    }
}
