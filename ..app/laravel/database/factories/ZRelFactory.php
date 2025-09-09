<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Vendor\Models\ZRel>
 */
class ZRelFactory
{
    protected $model = \Vendor\Models\ZRel::class;
    public $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create('pt_BR');
    }

    public function definition()
    {
        return [
            'table__' => $this->faker->sentence(5),
            'id__' => $this->faker->numberBetween(1, 100),
            'fields' => $this->faker->lexify('????????????????????'),
            'value' => $this->faker->optional(0.8)->paragraph(5),
        ];
    }
}
