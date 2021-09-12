<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Handler\InvoiceCreationHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CreateInvoice extends AbstractController
{
    private $invoiceCreationHandler;

    public function __construct(InvoiceCreationHandler $invoiceCreationHandler)
    {
        $this->invoiceCreationHandler = $invoiceCreationHandler;
    }

    public function __invoke(Invoice $data): Invoice
    {
        $this->invoiceCreationHandler->handle($data);

        return $data;
    }
}
