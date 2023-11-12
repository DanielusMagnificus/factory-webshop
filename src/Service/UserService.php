<?php

namespace App\Service;

use App\Entity\Country;
use App\Entity\DeliveryAddress;
use App\Entity\User;
use App\Exceptions\UserExistsException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserService {

    private UserRepository $userRepository;

    function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    function findAll(): array {
        return $this->userRepository->findAll();
    }

    function findOneById(int $id) : User {
        $user = $this->userRepository->findOneById($id);
        if ($user) {
            return $user;
        }

        throw new Exception("No user found.");
    }

    function registerUserWithInfo($firstName, $lastName, $phoneNumber, $email) : void {
        $user = new User();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setPhoneNumber($phoneNumber);
        $user->setEmail($email);

        if ($this->userExists($user)) {
            throw new Exception("User already exists.");
        }
        
        $this->userRepository->save($user);

    }

    /**
     * checks whether user exists and returns appropriate boolean value
     */
    function userExists(User $user) : bool {
        $allUsers = $this->userRepository->findAll();
        foreach ($allUsers as $dbUser) {
            if ($dbUser->getEmail() === $user->getEmail()) {
                return true;
            }

            if ($dbUser->getPhoneNumber() === $user->getPhoneNumber()) {
                return true;
            }
        }

        return false;
    }

    public function addDeliveryAddressForUser(int $userId, string $countryCode, string $address, string $apartmentNumber) : void {
        $user = $this->userService->findOneById($userId);
        $country = $this->countryService->findCountryByCode($countryCode);

        $deliveryAddress = new DeliveryAddress();
        $deliveryAddress->setAddress($address);
        $deliveryAddress->setApartmentNumber($apartmentNumber);
        $deliveryAddress->setUser($user);
        $deliveryAddress->setCountry($country);

        $this->saveDeliveryAddress($deliveryAddress);
    }

    public function saveDeliveryAddress(DeliveryAddress $deliveryAddress): void {
        $this->entityManager->persist($deliveryAddress);
        $this->entityManager->flush();
    }
}