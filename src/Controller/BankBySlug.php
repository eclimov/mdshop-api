<?php

namespace App\Controller;

use App\Entity\Bank;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class BankBySlug extends AbstractController
{
    public function __invoke(string $slug)
    {
        $bank = $this->getDoctrine()
            ->getRepository(Bank::class)
            ->findBy(
                ['slug' => $slug],
            );

        if (!$bank) {
            throw $this->createNotFoundException(
                'No bank found for this slug'
            );
        }

        return $bank;
    }
}
