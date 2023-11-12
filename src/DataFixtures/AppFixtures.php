<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\CountryFixtures;
use App\DataFixtures\PurchasedProductFixtures;
use App\DataFixtures\ProductCategoryFixtures;
use App\Service\UserService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $userFixtures = new UserFixtures();
        $usersList = $userFixtures->load($manager, 20);
        
        $countryFixtures = new CountryFixtures();
        $countriesList = $countryFixtures->load($manager);

        $productFixtures = new ProductFixtures();
        $productsList = $productFixtures->load($manager, 80);

        $storesFixtures = new StoreFixtures();
        $storesList = $storesFixtures = $storesFixtures->load($manager);

        $priceListFixtures = new PriceListFixtures();
        $priceList = $priceListFixtures->load($manager, $productsList, $storesList);

        $contractListFixtures = new ContractListFixtures();
        $contractListFixtures->load($manager, $priceList, $usersList, $productsList);

        $discountFixtures = new DiscountFixtures();
        $discountFixtures->load($manager);

        $categoryFixtures = new CategoryFixtures();
        $categoriesList = $categoryFixtures->load($manager);

        $deliveryAddressFixtures = new DeliveryAddressFixtures();
        $deliveryAddressFixtures->load($manager, $usersList, $countriesList);

        $productCategoryFixtures = new ProductCategoryFixtures();
        $productCategoryFixtures->load($manager, $productsList, $categoriesList);

    }
}
