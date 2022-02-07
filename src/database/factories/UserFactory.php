<?php

namespace Database\Factories;

use App\Models\User\User;
use App\Entities\User\User as UserEntity;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => UserEntity::TYPE_COMMON,
            'fullname' => $this->faker->name,
            'cpf' => $this->faker->cpf,
            'cnpj' => $this->faker->cnpj,
            'email' => $this->faker->email,
            'password' => $this->faker->password,
            'available_money' => $this->faker->randomDigit

        ];
    }

    /**
     * Indicate that the user is shopkeeper.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function shopkeeper()
    {
        return $this->state(function () {
            return [
                'type' => UserEntity::TYPE_SHOPKEEPER
            ];
        });
    }

}
