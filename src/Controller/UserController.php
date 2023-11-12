<?php

namespace App\Controller;

use App\Service\DeliveryAddressService;
use App\Service\ProductService;
use App\Service\UserService;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    private UserService $userService;
    private DeliveryAddressService $deliveryAddressService;

    function __construct(UserService $userService, DeliveryAddressService $deliveryAddressService) {
            $this->userService = $userService;
            $this->deliveryAddressService = $deliveryAddressService;
    }

    /**
     * lists all users
     * RequestParams: {}
     */
    #[Route('/list-all-users', name: 'list_all_users')]
    public function listAllUsers(): JsonResponse
    {
        $allUsers = $this->userService->findAll();

        $formattedUsers = [];
        foreach ($allUsers as $user) {
            $formattedUsers[] = [
                "id" => $user->getId(),
                "firstName" => $user->getFirstName(),
                "lastName" => $user->getLastName(),
                "phoneNumber" => $user->getPhoneNumber(),
                "email" => $user->getEmail()
            ];
        }

        return $this->json($formattedUsers);
    }

    /**
     * RequestParams: {firstName, lastName, phoneNumber, email}
     */
    #[Route('/registration', name: 'registration', methods: ['POST'])]
    public function registration(Request $request): JsonResponse
    {
        $firstName = $request->query->get('firstName');
        $lastName = $request->query->get('lastName');
        $phoneNumber = $request->query->get('phoneNumber');
        $email = $request->query->get('email');

        try {
            $result = $this->userService->registerUserWithInfo($firstName, $lastName, $phoneNumber, $email);
            return $this->json([
                'message' => 'User saved successfully.'
            ]);
        } catch (Exception $e) {
            return $this->json([
                'error' => $e->getMessage(),
            ], JsonResponse::HTTP_BAD_REQUEST);
        } 
    }

    /**
     * RequestParams: {address, apartment-number, country-code}
     */
    #[Route('/user/{userId}/add-delivery-address', name: 'add_delivery_address', methods: ["POST"])]
    public function addDeliveryAddress(Request $request, int $userId): JsonResponse
    {

        $queryArray = [
            'userId' => $userId,
            'countryCode' => $request->query->get('countryCode'),
            'address' => $request->query->get('address'),
            'apartmentNumber' => $request->query->get('apartmentNumber')
        ];

        try {
            $this->deliveryAddressService->addDeliveryAddressForUser($queryArray);
    
            return $this->json([
                'message' => 'Delivery address added successfully.',
            ]);
        } catch (Exception $e) {
            return $this->json([
                'error' => $e->getMessage(),
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

    }
}
