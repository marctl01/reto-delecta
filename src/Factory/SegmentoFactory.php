<?php

namespace App\Factory;

use App\Entity\Segmento;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SegmentoFactory
{
    private $validator;

    public function __construct(?ValidatorInterface $validator = null)
    {
        $this->validator = $validator;
    }

    public function createSegmento(array $segmentoData): Segmento
    {
        $segmento = new Segmento();
        $segmento->setName($segmentoData['name']);
        $segmento->setSize($segmentoData['size']);
        $segmento->setUidentifier($segmentoData['uidentifier']);

        // Validar la entidad Segmento
        $errors = $this->validator->validate($segmento);
        if (count($errors) > 0) {
            // Manejar errores de validación
            // Aquí puedes lanzar una excepción, loggear los errores, o manejarlos de otra manera según tus necesidades
            // Por ejemplo, si deseas lanzar una excepción:
            // throw new \InvalidArgumentException((string) $errors);
        }

        return $segmento;
    }
}
