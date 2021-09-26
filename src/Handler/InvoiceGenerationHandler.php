<?php

namespace App\Handler;

use App\Entity\Invoice;
use App\Exception\NotFoundException;
use App\Service\InvoiceGenerator;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Writer\Exception;

class InvoiceGenerationHandler
{
    private InvoiceGenerator $invoiceGenerator;
    private EntityManagerInterface $em;

    /**
     * @param InvoiceGenerator $invoiceGenerator
     * @param EntityManagerInterface $em
     */
    public function __construct(InvoiceGenerator $invoiceGenerator, EntityManagerInterface $em)
    {
        $this->invoiceGenerator = $invoiceGenerator;
        $this->em = $em;
    }


    /**
     * @throws Exception
     * @throws NotFoundException
     */
    public function handle(int $invoiceId): void
    {
        /**
         * @var $invoice Invoice
         */
        $invoice = $this->em->getRepository(Invoice::class)->find($invoiceId);
        if (!$invoice) {
            throw new NotFoundException(sprintf('The invoice %d does not exist.', $invoiceId));
        }
         $this->invoiceGenerator->generate($invoice, $this->em);
    }
}
