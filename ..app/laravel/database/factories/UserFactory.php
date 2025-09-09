<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Vendor\Models\Users>
 */
class UserFactory
{
    protected $model = \Vendor\Models\Users::class;
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
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->optional(0.8)->numerify('(##) #####-####'),
            'users' => $this->faker->optional(0.8)->numberBetween(1, 100),
            'permissions' => $this->faker->optional(0.8)->paragraph(5),
            'permissions_all' => $this->faker->numberBetween(1, 100),
            'password' => Hash::make('password'),
            'remember_token' => $this->faker->optional(0.8)->lexify('??????'),
            'verified_at' => $this->faker->optional(0.8)->dateTimeBetween('-2 years', 'now') ? $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s') : null,
        ];
    }
}
