<?php

namespace App\DataFixtures;

use App\Entity\Images;
use App\Entity\Products;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ImagesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($img = 1; $img <= 20 ; $img++) { 
            $image = new Images();
            $image->setName($faker->image(null, 640, 480));

            // chercher un product
            $product = $this->getReference('prod-' . rand(1, 10));
            $image->setProducts($product);

            $manager->persist($image);
        }
        $manager->flush();
    }

    // pour excuter ProductsFixtures avant ImagesFixtures
    public function getDependencies(): array
    {
       return [
            ProductsFixtures::class
       ];
    }
}