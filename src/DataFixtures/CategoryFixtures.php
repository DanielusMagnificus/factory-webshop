<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;

class CategoryFixtures
{
    public function load(ObjectManager $manager): array
    {
        $categoriesList = [];

        $all = new Category();
        $all->setName("All");
        $all->setDescription("root category");
        $manager->persist($all);
        array_push($categoriesList, $all);

        $computer = new Category();
        $computer->setName("Computers");
        $computer->setDescription("desktops and laptops");
        $computer->setParentCategory($all);
        $manager->persist($computer);
        array_push($categoriesList, $computer);

        $desktop = new Category();
        $desktop->setName("Desktops");
        $desktop->setDescription("desktops");
        $desktop->setParentCategory($computer);
        $manager->persist($desktop);
        array_push($categoriesList, $desktop);

        $laptop = new Category();
        $laptop->setName("Laptops");
        $laptop->setDescription("laptops");
        $laptop->setParentCategory($computer);
        $manager->persist($laptop);
        array_push($categoriesList, $laptop);

        $acer = new Category();
        $acer->setName("Acer");
        $acer->setDescription("acer");
        $acer->setParentCategory($laptop);
        $manager->persist($acer);
        array_push($categoriesList, $acer);

        $manager->flush();
        return $categoriesList;
    }
}
