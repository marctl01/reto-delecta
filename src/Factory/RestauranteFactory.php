<?php

namespace App\Factory;

use App\Entity\Restaurante;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RestauranteFactory
{
    private $validator;

    public function __construct(?ValidatorInterface $validator = null)
    {
        $this->validator = $validator;
    }

    public function createRestaurante(array $restauranteData): Restaurante
    {
        $restaurante = new Restaurante();
        $restaurante->setName($restauranteData['name']);
        $restaurante->setStreetAddress($restauranteData['street_address']);
        $restaurante->setLatitude($restauranteData['latitude']);
        $restaurante->setLongitude($restauranteData['longitude']);
        $restaurante->setCityName($restauranteData['city_name']);
        $restaurante->setPopularityRate($restauranteData['popularity_rate']);
        $restaurante->setSatisfactionRate($restauranteData['satisfaction_rate']);
        $restaurante->setLastAvgPrice($restauranteData['last_avg_price']);
        $restaurante->setTotalReviews($restauranteData['total_reviews']);
        $restaurante->setUidentifier($restauranteData['uidentifier']);

        // Validar la entidad Restaurante
        $errors = $this->validator->validate($restaurante);
        if (count($errors) > 0) {
            // Manejar errores de validación
            // Aquí puedes lanzar una excepción, loggear los errores, o manejarlos de otra manera según tus necesidades
            // Por ejemplo, si deseas lanzar una excepción:
            // throw new \InvalidArgumentException((string) $errors);
        }

        return $restaurante;
    }
}
