<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Vendor\Models\CustomersAddress>
 */
class CustomersAddressFactory
{
    protected $model = \Vendor\Models\CustomersAddress::class;
    public $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create('pt_BR');
    }

    public function definition()
    {
        return [
            'customers' => DB::table('customers_address')->inRandomOrder()->value('id') ?: 1,
            'main' => $this->faker->numberBetween(1, 100),
            'name' => $this->faker->optional(0.8)->name(),
            'cpf_cnpj' => $this->faker->optional(0.8)->lexify('??????????????????'),
            'email' => $this->faker->optional(0.8)->unique()->safeEmail(),
            'phone' => $this->faker->optional(0.8)->numerify('(##) #####-####'),
            'address' => $this->faker->streetName(),
            'number' => $this->faker->buildingNumber(),
            'complement' => $this->faker->optional(0.3)->randomElement([null, $this->faker->secondaryAddress()]),
            'neighborhood' => $this->faker->citySuffix(),
            'city' => $this->faker->city(),
            'uf' => $this->faker->stateAbbr(),
            'zipcode' => $this->faker->numerify('#####-###'),
        ];
    }
}
