<?php

namespace App\Controller;

use App\Service\DiscountService;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{

    private OrderService $orderService;
    private DiscountService $discountService;

    function __construct(OrderService $orderService, DiscountService $discountService) {
            $this->orderService = $orderService;
            $this->discountService = $discountService;
    }

    /**
     * adds requested products to purchased_products and creates an order
     * BodyParam: JSON list
     * RequestParam: {discountCode}
     */
    #[Route('/order/user/{userId}/add-order', name: 'add-order', methods: ["POST"])]
    public function addOrder(Request $request, int $userId): JsonResponse
    {
        $productsJson = json_decode($request->getContent(), true);

        $queryArray = [
            'userId' => $userId,
            'addressId' => $request->query->get('addressId', 1),
            'discountCode' => $request->query->get('discount', '')
        ];

        $activatedDiscounts = [];
        array_push($activatedDiscounts, $queryArray['discountCode']);

        if ($productsJson === null) {
            
            return $this->json([
                'message' => 'List of products required.',
            ]);
        }

        $this->orderService->placeOrderForUserIdOnAddressId($queryArray, $productsJson, $activatedDiscounts);

        return $this->json([
            'message' => 'order placed successfully.',
        ]);


    }

    /**
     * shows active discounts on selected order
     * RequestParam: {}
     */
    #[Route('/order/{orderId}/discounts', name: 'show_activated_discounts')]
    public function showActivatedDiscounts(Request $request, int $orderId): JsonResponse
    {
        $queryArray = [
            'orderId' => $orderId
        ];

        $activatedDiscounts = $this->discountService->getActivatedDiscountsForOrder($queryArray);

        $formattedDiscounts = [];
        foreach ($activatedDiscounts as $discount) {
            $formattedDiscounts[] = [
                'code' => $discount->getCode(),
                'value' => $discount->getValue()
            ];
        }
        return $this->json($formattedDiscounts);
    }

}
