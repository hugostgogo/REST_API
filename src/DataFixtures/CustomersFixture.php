<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

// import customer class
use App\Entity\Customer;

// import password hasher
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

// import faker
use Faker\Factory;

class CustomersFixture extends Fixture
{

    public $faker;

    public $passwordHasher;

    static public $testCustomer = [
        'email' => 'test@example.com',
        'password' => '123456789',
    ];

    static $customersCount = 5;

    public function __construct()
    {
        // init password hasher
        $passwordHasherFactory = new PasswordHasherFactory([
            PasswordAuthenticatedUserInterface::class => ['algorithm' => 'auto'],
        ]);
        $this->passwordHasher = new UserPasswordHasher($passwordHasherFactory);

        // init faker
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {

        // Create default customer
        $customer = new Customer();

        // set customer attributes
        $customer->setEmail(self::$testCustomer['email']);
        $customer->setPassword($this->passwordHasher->hashPassword($customer, self::$testCustomer['password']));
        $customer->setRoles(['ROLE_USER']);

        $manager->persist($customer);

        // Create 10 customers
        for ($i = 0; $i < self::$customersCount; $i++) {
            $customer = new Customer();

            // set customer attributes
            $customer->setRoles(["ROLE_USER"]);
            $customer->setEmail("customer" . $i . "@example.com");
            $customer->setPassword($this->passwordHasher->hashPassword($customer, "password" . $i));

            $manager->persist($customer);

            // add to reference
            $this->addReference("customer" . $i, $customer);
        }

        // flush
        $manager->flush();
    }
}
