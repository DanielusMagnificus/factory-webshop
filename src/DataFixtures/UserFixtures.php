<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class UserFixtures
{
    public function load(ObjectManager $manager, int $numberToGenerate): array
    {
        $usersList = [];
        for ($i = 0; $i <= $numberToGenerate; $i++) {
           $user = new User();
            $user->setFirstName("First" . $i);
            $user->setLastName("Last" . $i);
            $user->setPhoneNumber("123" . $i);
            $user->setEmail("Email" . $i);
            
            $manager->persist($user);
            array_push($usersList, $user);
        }

        $manager->flush();
        return $usersList;
    }
}
