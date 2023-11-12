<?php

namespace App\DataFixtures;

use App\Entity\DeliveryAddress;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class DeliveryAddressFixtures
{
    public function load(ObjectManager $manager, array $usersList, array $countriesList): void
    {
        
        foreach ($usersList as $user) {
            $country = $countriesList[array_rand($countriesList)];
            $deliveryAddress = new DeliveryAddress();
            $deliveryAddress->setAddress("Address in " . $country->getName());
            $deliveryAddress->setApartmentNumber(rand(1, 200));
            $deliveryAddress->setUser($user);
            $deliveryAddress->setCountry($country);
            
            $manager->persist($deliveryAddress);
        }

        $manager->flush();
    }
}
