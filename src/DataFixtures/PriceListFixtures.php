<?php

namespace App\DataFixtures;

use App\Entity\PriceList;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PriceListFixtures
{
    public function load(ObjectManager $manager, array $productsList, array $storeList): array
    {
        $allPriceLists = [];

        // generate a number of price list using random number of random products from productsList and give it a random price
        $randomProductsSelected = [];
        $randomNumberOfProducts = rand((int) count($productsList) / 2, count($productsList));
        $randomIdsList = array_rand($productsList, $randomNumberOfProducts);
        foreach ($randomIdsList as $randomId) {
            $randomProduct = $productsList[$randomId];

            $randomPrice = rand(10, 100);
            $randomStore = $storeList[rand(0,2)];

            $priceList = new PriceList();
            $priceList->setName($randomProduct->getName());
            $priceList->setPrice($randomPrice);
            $priceList->setSKU($randomProduct->getSKU());
            $priceList->setProduct($randomProduct);
            $priceList->setStore($randomStore);

            $manager->persist($priceList);
            array_push($allPriceLists, $priceList);
        }
  
        $manager->flush();
        return $allPriceLists;
    }
}
