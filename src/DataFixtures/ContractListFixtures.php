<?php

namespace App\DataFixtures;

use App\Entity\ContractList;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class ContractListFixtures
{
    public function load(ObjectManager $manager, array $priceList, array $usersList, array $productsList)
    {
        $allContractLists = [];

        // generate a number of price list using random number of random products from productsList and give it a random price
        $randomProductsSelected = [];
        $randomNumberOfProducts = rand((int) count($priceList) / 2, count($priceList) - 1);
        $randomIdsList = array_rand($priceList, $randomNumberOfProducts);
        foreach ($randomIdsList as $randomId) {
            $randomProduct = $priceList[$randomId];

            $randomStore = $usersList[rand(0, count($usersList) - 1)];

            $contractList = new ContractList();
            $contractList->setPrice($randomProduct->getPrice() - rand(1, (int) $randomProduct->getPrice() / 2));
            $contractList->setSKU($randomProduct->getSKU());
            $contractList->setProduct($randomProduct->getProduct());
            $contractList->setUser($randomStore);

            $manager->persist($contractList);
            // array_push($allcontractLists, $contractList);
        }
  
        $manager->flush();
        // return $allcontractLists;
    }
}
