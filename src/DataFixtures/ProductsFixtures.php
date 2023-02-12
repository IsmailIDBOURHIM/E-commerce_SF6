<?php

namespace App\DataFixtures;

use App\Entity\Products;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class ProductsFixtures extends Fixture
{
    public function __construct(private SluggerInterface $sluggerInterface)
    {
    }
    
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($prod = 1; $prod <= 10; $prod++) { 
            $product = new Products();
            $product->setName($faker->text(20));
            $product->setDescription($faker->text());
            $product->setPrice($faker->numberBetween($min = 200, $max = 2000));
            $product->setStock($faker->numberBetween($min = 0, $max = 15));
            $product->setSlug($this->sluggerInterface->slug($product->getName())->lower());
            
            // chercher une référence de category
            $category = $this->getReference('cat-'. rand(1, 8));
            $product->setCategories($category);

            // referencier les products
            $this->setReference('prod-' . $prod, $product);
            
            $manager->persist($product);
        }

        $manager->flush();
    }
}