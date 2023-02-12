<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoriesFixtures extends Fixture
{
    private $counter = 1;
    public function __construct(private SluggerInterface $sluggerInterface)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $parent = $this->createCategory('Informatique', null, $manager);
        $this->createCategory('Ordinateurs portables', $parent, $manager);
        $this->createCategory('Ecrans', $parent, $manager);
        $this->createCategory('Souris', $parent, $manager);

        $parent_1 = $this->createCategory('Image et son', null, $manager);
        $this->createCategory('Casques', $parent_1, $manager);
        $this->createCategory('Ecouteurs', $parent_1, $manager);
        $this->createCategory('Caméras', $parent_1, $manager);
        
        $manager->flush();
    }

    public function createCategory(string $name, Categories $parent = null, ObjectManager $manager)
    {
        $category = new Categories();
        $category->setName($name);
        $category->setSlug($this->sluggerInterface->slug($category->getName())->lower());
        $category->setParent($parent);
        
        // référencier les categories
        $this->addReference('cat-'. $this->counter, $category);
        $this->counter++;
        
        $manager->persist($category);
        
        return $category;
    }
}