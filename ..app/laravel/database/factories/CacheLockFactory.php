<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Vendor\Models\CacheLocks>
 */
class CacheLockFactory
{
    protected $model = \Vendor\Models\CacheLocks::class;
    public $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create('pt_BR');
    }

    public function definition()
    {
        return [
            'key' => $this->faker->sentence(5),
            'owner' => $this->faker->sentence(5),
            'expiration' => $this->faker->numberBetween(1, 100),
        ];
    }
}
