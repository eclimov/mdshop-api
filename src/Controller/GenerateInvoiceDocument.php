<?php

namespace App\Controller;

use App\Exception\NotFoundException;
use App\Handler\InvoiceGenerationHandler;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class GenerateInvoiceDocument extends AbstractController
{
    /**
     * @throws Exception
     * @throws NotFoundException
     */
    public function __invoke(
        int $id,
        InvoiceGenerationHandler $invoiceCreationHandler
    )
    {
        $invoiceCreationHandler->handle($id);
        return null;
    }
}
