<?php

namespace App\Service;

use App\Entity\Discount;
use App\Entity\Order;
use App\Repository\DiscountRepository;
use App\Repository\OrderRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class DiscountService {

    private DiscountRepository $discountRepository;
    private OrderRepository $orderRepository;
    private EntityManagerInterface $entityManager;

    function __construct(DiscountRepository $discountRepository, OrderRepository $orderRepository, EntityManagerInterface $entityManager) {
        $this->discountRepository = $discountRepository;
        $this->orderRepository = $orderRepository;
        $this->entityManager = $entityManager;
    }

    function findAll() : array {
        return $this->discountRepository->findAll();
    }

    function findOneByCode(string $code) : Discount {
        $discount = $this->discountRepository->findOneByCode($code);
        if ($discount) {
            return $discount;
        }

        throw new Exception("No discount found.");
    }

    function applyDiscounts(array $activeCodeList, float $price, Order $order) : Order {
        $currentDateTime = new DateTime();
        $discounts = $this->discountRepository->findActiveDiscountsByCodesArray($activeCodeList, $currentDateTime);

        foreach ($discounts as $discount) {
            $price *= 1 - ($discount->getValue() / 100);
            $order->addDiscount($discount);
        }
        $order->setPrice($price);
        return $order;
    }

    function getActivatedDiscountsForOrder(array $queryArray) : array {
        $orderId = $queryArray['orderId'];
        $order = $this->orderRepository->findOneById($orderId);
        return $this->discountRepository->getActivatedDiscountsForOrder($order);
    }
}