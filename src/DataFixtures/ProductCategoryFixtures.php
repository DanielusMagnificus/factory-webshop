<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class ProductCategoryFixtures
{
    public function load(ObjectManager $manager, array $productsList, array $categoriesList): void
    {
        
        foreach ($productsList as $product) {
            $randomCategory = $categoriesList[rand(0, count($categoriesList) - 1)];
            $product->addCategory($randomCategory);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
