<?php

namespace App\Service;

use App\Entity\CompanyAddress;
use App\Entity\Invoice;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use function count;

class InvoiceGenerator
{
    /**
     * @var string
     */
    private string $targetDirectory;

    /**
     * @var XlsProcessor
     */
    private XlsProcessor $xlsProcessor;

    /**
     * @param string $targetDirectory
     * @param XlsProcessor $xlsProcessor
     */
    public function __construct(string $targetDirectory, XlsProcessor $xlsProcessor)
    {
        $this->targetDirectory = $targetDirectory;
        $this->xlsProcessor = $xlsProcessor;
    }

    /**
     * @param Invoice $invoice
     * @param EntityManagerInterface $doctrine
     * @return string
     * @throws Exception
     */
    public function generate(Invoice $invoice, EntityManagerInterface $doctrine): string
    {
        $spreadsheet = $this->getXlsProcessor()
            ->getSpreadSheet($this->getInvoiceDirectory() . '/' . 'template.xlsx');

        $sheet = $spreadsheet->getActiveSheet();

        $seller = $invoice->seller;
        $buyer = $invoice->buyer;
        $carrier = $invoice->carrier;

        $sheet->setCellValue(
            'C2',
            $invoice->orderDate->format('d.m.Y')
        );
        $sheet->setCellValue(
            'D2',
            $invoice->deliveryDate->format('d.m.Y')
        );
        $sheet->setCellValue(
            'I4',
            $carrier->shortName
        );

        /**
         * @var $sellerJuridicAddresses CompanyAddress[]
         */
        $sellerJuridicAddresses = $doctrine
            ->getRepository(CompanyAddress::class)
            ->findJuridicByCompany($seller);
        $sheet->setCellValue(
            'C5',
            $seller->name
            . ' IBAN ' . $seller->iban
            . ' ' . $seller->bankAffiliate->affiliateNumber
            . ' a.j.' . $sellerJuridicAddresses[0]->address
        );

        /**
         * @var $buyerJuridicAddresses CompanyAddress[]
         */
        $buyerJuridicAddresses = $doctrine
            ->getRepository(CompanyAddress::class)
            ->findJuridicByCompany($buyer);
        $sheet->setCellValue(
            'C6',
            $buyer->name
            . ' IBAN ' . $buyer->iban
            . ' ' . $buyer->bankAffiliate->affiliateNumber
            . ' ' . (count($buyerJuridicAddresses) > 0 ? ('a.j.' .$buyerJuridicAddresses[0]->address) : '')
        );
        $sheet->setCellValue(
            'O4',
            $carrier->fiscalCode . ' / ' . $carrier->vat
        );
        $sheet->setCellValue(
            'O5',
            $seller->fiscalCode . ' / ' . $seller->vat
        );
        $sheet->setCellValue(
            'O6',
            $buyer->fiscalCode . ' / ' . $buyer->vat
        );
        $sheet->setCellValue(
            'M7',
            $invoice->attachedDocument
        );
        $loadingPoint = $invoice->loadingPoint;
        $sheet->setCellValue(
            'C9',
            ($loadingPoint !== null) ? $loadingPoint->address : ''
        );
        $unloadingPoint = $invoice->unloadingPoint;
        $sheet->setCellValue(
            'G9',
            ($unloadingPoint !== null) ? $unloadingPoint->address : ''
        );
        $approvedByEmployee = $invoice->approvedByEmployee;
        $sheet->setCellValue(
            'C25',
            trim($approvedByEmployee->position . ' ' . $approvedByEmployee->name)
        );
        $processedByEmployee = $invoice->processedByEmployee;
        $sheet->setCellValue(
            'C27',
            trim($processedByEmployee->position . ' ' . $processedByEmployee->name)
        );
        $sheet->setCellValue('K28', $invoice->recipientName);

        $fileName = $invoice->getId() . '.xlsx';
        $this->getXlsProcessor()->save(
            $spreadsheet,
            $this->getInvoiceDirectory(),
            $fileName
        );

        return $this->getInvoiceDirectory() . '/' . $fileName;
    }

    /**
     * @return string
     */
    public function getInvoiceDirectory(): string
    {
        return $this->targetDirectory;
    }

    /**
     * @return XlsProcessor
     */
    public function getXlsProcessor(): XlsProcessor
    {
        return $this->xlsProcessor;
    }
}
