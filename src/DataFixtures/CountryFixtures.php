<?php

namespace App\DataFixtures;

use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class CountryFixtures
{
    public function load(ObjectManager $manager): array
    {
        $countriesList = [];
        $croatia = new Country();
        $croatia->setName("Croatia");
        $croatia->setTaxPercentage(25);
        $croatia->setCode("HR");
        $manager->persist($croatia);
        array_push($countriesList, $croatia);

        $germany = new Country();
        $germany->setName("Germany");
        $germany->setTaxPercentage(19);
        $germany->setCode("DE");
        $manager->persist($germany);
        array_push($countriesList, $germany);

        $austria = new Country();
        $austria->setName("Austria");
        $austria->setTaxPercentage(20);
        $austria->setCode("AT");
        $manager->persist($austria);
        array_push($countriesList, $austria);

        $manager->flush();
        return $countriesList;
    }
}
