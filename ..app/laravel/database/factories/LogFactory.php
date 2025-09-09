<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Vendor\Models\Logs>
 */
class LogFactory
{
    protected $model = \Vendor\Models\Logs::class;
    public $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create('pt_BR');
    }

    public function definition()
    {
        return [
            'type' => $this->faker->lexify('????????????????????'),
            'name' => $this->faker->name(),
            'action' => $this->faker->lexify('????????????????????'),
            'ip' => $this->faker->lexify('????????????????????'),
            'date_exit' => $this->faker->optional(0.8)->dateTimeBetween('-2 years', 'now') ? $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s') : null,
        ];
    }
}
