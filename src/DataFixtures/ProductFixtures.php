<?php

namespace App\DataFixtures;

use App\Entity\Product;
use DateTime;
use DateTimeZone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class ProductFixtures
{
    public function load(ObjectManager $manager, int $numberToGenerate): array
    {
        $productsList = [];
        for ($i = 0; $i < $numberToGenerate; $i++) {
            $product = new Product();
            $product->setName("Product" . $i);
            $product->setDescription("Description for Product" . $i);
            $product->setSKU("HR-ZGZTX-BLN-" . $i);
            $product->setPublished(new DateTime('now', new DateTimeZone('CET')));
            
            $manager->persist($product);
            array_push($productsList, $product);
        }

        $manager->flush();
        return $productsList;
    }
}
