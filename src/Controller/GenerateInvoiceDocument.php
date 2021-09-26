<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Handler\InvoiceGenerationHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class GenerateInvoiceDocument extends AbstractController
{
    public function __invoke(
        int $id,
        InvoiceGenerationHandler $invoiceCreationHandler
    )
    {
        $invoiceCreationHandler->handle($id);
        return null;
    }
}
