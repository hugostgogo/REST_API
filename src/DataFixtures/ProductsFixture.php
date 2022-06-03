<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

// import Product class
use App\Entity\Product;

// import faker
use Faker\Factory;

class ProductsFixture extends Fixture
{

    public $faker;

    static public $productsCount = 30;

    public function __construct()
    {
        // init faker
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        // create 10 products
        for ($i = 0; $i < self::$productsCount; $i++) {
            $product = new Product();

            // set product attributes
            $product->setName("PRODUIT " . $i);
            $product->setDescription("Description du produit " . $i);
            $product->setPrice($this->faker->randomFloat(2, 0, 20));

            $manager->persist($product);
        }

        // save products
        $manager->flush();

    }
}
