<?php

namespace App\Service;

use App\Entity\Country;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class CountryService {

    private CountryRepository $countryRepository;

    function __construct(EntityManagerInterface $entityManager, CountryRepository $countryRepository) {
        $this->entityManager = $entityManager;
        $this->countryRepository = $countryRepository;
    }

    function findCountryByCode(string $code) : Country {
        
        $country = $this->countryRepository->findOneByCode($code);
        if ($country) {
            return $country;
        }

        throw new Exception("No country found.");
    }

    function findOneById(int $id) : Country {
        $country = $this->countryRepository->findOneById($id);
        if ($country) {
            return $country;
        }

        throw new Exception("No country found.");
    }

}