<?php

namespace App\Service;

use App\Entity\DeliveryAddress;
use App\Entity\User;
use App\Repository\CountryRepository;
use App\Repository\DeliveryAddressRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeliveryAddressService {

    private DeliveryAddressRepository $deliveryAddressRepository;
    private CountryRepository $countryRepository;
    private UserRepository $userRepository;

    function __construct(DeliveryAddressRepository $deliveryAddressRepository, CountryRepository $countryRepository, UserRepository $userRepository) 
    {
        $this->deliveryAddressRepository = $deliveryAddressRepository;
        $this->countryRepository = $countryRepository;
        $this->userRepository = $userRepository;
    }

    function saveDeliveryAddress(DeliveryAddress $deliveryAddress) : void 
    {
        $this->deliveryAddressRepository->save($deliveryAddress);
    }

    function findDeliveryAddressByUser(User $user) : array 
    {
        return $this->deliveryAddressRepository->findDeliveryAddressByUser($user);
    }

    function findDeliveryAddressByIdAndUser(int $addressId, User $user) : DeliveryAddress 
    {
        return $this->deliveryAddressRepository->findDeliveryAddressByIdAndUser($addressId, $user);
    }

    function addDeliveryAddressForUser($queryArray) : void 
    {
        $userId = $queryArray['userId'];
        $countryCode = $queryArray['countryCode'];
        $address = $queryArray['address'];
        $apartmentNumber = $queryArray['apartmentNumber'];

        $country = $this->countryRepository->findOneByCode($countryCode);
        $user = $this->userRepository->findOneById($userId);

        if (!$user) {
            throw new Exception("Invalid User.");
        }

        if (!$country) {
            throw new Exception("Invalid Country");
        }

        $deliveryAddress = new DeliveryAddress();
        $deliveryAddress->setAddress($address);
        $deliveryAddress->setApartmentNumber($apartmentNumber);
        $deliveryAddress->setUser($user);
        $deliveryAddress->setCountry($country);

        $this->deliveryAddressRepository->save($deliveryAddress);
    }
}