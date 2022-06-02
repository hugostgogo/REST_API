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
        $customer = new Customer();

        $customer->setEmail('test@example.com');

        // set Roles
        $customer->setRoles(['ROLE_USER']);
        

        // create random plain password
        $plainPassword = "123456789";

        // Log password
        echo "Plain password: " . $plainPassword . "\n";

        // hash password
        $hashedPassword = $this->passwordHasher->hashPassword($customer, $plainPassword);

        // set password
        $customer->setPassword($hashedPassword);

        // save customer
        $manager->persist($customer);

        // flush
        $manager->flush();
    }
}
