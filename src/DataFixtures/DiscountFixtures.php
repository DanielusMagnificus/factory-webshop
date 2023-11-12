<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Discount;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class DiscountFixtures
{
    public function load(ObjectManager $manager): void
    {
        $discount = new Discount();
        $discount->setCode("OVER_100_EURO");
        $discount->setValue(10);
        $discount->setDateStart(new DateTime());
        $discount->setDateEnd(new DateTime('2024-11-10'));
        $manager->persist($discount);

        $discount = new Discount();
        $discount->setCode("CHRISTMAS_DISCOUNT");
        $discount->setValue(12);
        $discount->setDateStart(new DateTime());
        $discount->setDateEnd(new DateTime('2024-11-10'));

        $manager->persist($discount);

        $manager->flush();
    }
}
