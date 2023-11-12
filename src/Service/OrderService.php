<?php

namespace App\Service;

use App\AppConstants;
use App\Entity\Country;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\PurchasedProduct;
use App\Entity\User;
use App\Repository\OrderRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class OrderService {

    private OrderRepository $orderRepository;
    private EntityManagerInterface $entityManager;
    private DiscountService $discountService;
    private UserService $userService;
    private DeliveryAddressService $deliveryAddressService;
    private CountryService $countryService;
    private ProductService $productService;

    function __construct(OrderRepository $orderRepository, 
        EntityManagerInterface $entityManager, 
        DiscountService $discountService, 
        UserService $userService,
        DeliveryAddressService $deliveryAddressService,
        ProductService $productService,
        CountryService $countryService
    ) {
        $this->orderRepository = $orderRepository;
        $this->entityManager = $entityManager;
        $this->discountService = $discountService;
        $this->userService = $userService;
        $this->deliveryAddressService = $deliveryAddressService;
        $this->countryService = $countryService;
        $this->productService = $productService;
    }

    function save(Order $order) : void {
        $this->orderRepository->save($order);
    }

    function placeOrderForUserIdOnAddressId(array $queryArray, array $productsJson, array $activatedDiscounts) : void {
        $userId = $queryArray['userId'];
        $addressId = $queryArray['addressId'];

        $user = $this->userService->findOneById($userId);
        $address = $this->deliveryAddressService->findDeliveryAddressByIdAndUser($addressId, $user);
        $country = $this->countryService->findOneById($address->getCountry()->getId());

        // make an order and purchasedproducts
        $productIdObjects = [];
        foreach ($productsJson as $product) {
            $dbProduct = new Product();
            $dbProduct->setId($product['id']);
            array_push($productIdObjects, $dbProduct);
        }

        // get all products that correspond to passed json list of products
        $allPricedProducts = $this->productService->getProductsByListId($user, $productIdObjects);
        $dbPriced = [];
        foreach ($allPricedProducts as $pricedProduct) {
            array_push($dbPriced, get_class($pricedProduct));
        }

        $this->createOrder($user, $allPricedProducts, $country, $activatedDiscounts);
    }

    /**
     * transactional method
     * creates an order and binds purchased products to it, along with price calculations
     */
    function createOrder(User $user, array $allPricedProducts, Country $country, array $activatedDiscounts) : bool {
        return $this->entityManager->transactional(function() use($user, $allPricedProducts, $country, $activatedDiscounts) {
            $order = new Order();
            $order->setUser($user);
            
            $totalPrice = 0;
            foreach ($allPricedProducts as $pricedProduct) {
                
                $purchasedProduct = $this->createPurchasedProductWithCountryTax($pricedProduct, $order, $country);

                $this->entityManager->persist($purchasedProduct);

                $order->addPurchasedProduct($purchasedProduct);
                $totalPrice += $purchasedProduct->getPriceAfterTax();
            }

            if ($totalPrice > AppConstants::LEVEL_1_PRICE_DISCOUNT_THRESHOLD) {
                array_push($activatedDiscounts, AppConstants::LEVEL_1_PRICE_DISCOUNT_NAME);
            }

            $order = $this->discountService->applyDiscounts($activatedDiscounts, $totalPrice, $order);

            $order->setPrice($totalPrice);
            $order->setOrderDateTime(new DateTime());
            $this->entityManager->persist($order);
            $this->entityManager->flush();
        });
    }

    function createPurchasedProductWithCountryTax(Product $pricedProduct, Order $order, Country $country) : PurchasedProduct {
        $purchasedProduct = new PurchasedProduct();
        $purchasedProduct->setProduct($pricedProduct);
        $purchasedProduct->setBasePrice($pricedProduct->getFinalPrice());

        $taxModifier = 1 + ($country->getTaxPercentage() / 100);

        $modifiedPrice = $pricedProduct->getFinalPrice() * $taxModifier;

        $purchasedProduct->setPriceAfterTax($modifiedPrice);
        $purchasedProduct->setOrder($order);
        return $purchasedProduct;
    }
}