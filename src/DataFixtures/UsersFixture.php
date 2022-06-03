<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

// import User class
use App\Entity\User;

// import faker
use Faker\Factory;

class UsersFixture extends Fixture
{
    public $faker;

    static public $usersCount = 15;

    public function __construct()
    {
        // init faker
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        // create 10 users
        for ($i = 0; $i < self::$usersCount; $i++) {
            $user = new User();

            $user->setEmail("user" . $i . "@example.com");
            $user->setFirstName($this->faker->firstName);
            $user->setLastName($this->faker->lastName);

            // Get random customer reference
            $customer = $this->getReference("customer" . $this->faker->numberBetween(0, 4));

            // set customer
            $user->setCustomer($customer);

            $manager->persist($user);

            // add to reference
            $this->addReference("user" . $i, $user);

        }

        $manager->flush();
    }
}
