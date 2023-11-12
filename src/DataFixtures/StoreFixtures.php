<?php

namespace App\DataFixtures;

use App\Entity\Store;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class StoreFixtures
{
    public function load(ObjectManager $manager): array
    {
        $storesList = [];

        $protis = new Store();
        $protis->setName("Protis");
        $manager->persist($protis);
        array_push($storesList, $protis);

        $hgSpot = new Store();
        $hgSpot->setName("HG Spot");
        $manager->persist($hgSpot);
        array_push($storesList, $hgSpot);

        $info = new Store();
        $info->setName("Info");
        $manager->persist($info);
        array_push($storesList, $info);

        $manager->flush();
        return $storesList;
    }
}
