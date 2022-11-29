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
            'K9',
            $invoice->orderDate->format('d.m.Y')
        );
        $sheet->setCellValue(
            'N9',
            $invoice->deliveryDate->format('d.m.Y')
        );
        $sheet->setCellValue(
            'AC10',
            $carrier->shortName
        );

        /**
         * @var $sellerJuridicAddresses CompanyAddress[]
         */
        $sellerJuridicAddresses = $doctrine
            ->getRepository(CompanyAddress::class)
            ->findJuridicByCompany($seller);
        $sheet->setCellValue(
            'H12',
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
            'H14',
            $buyer->name
            . ' IBAN ' . $buyer->iban
            . ' ' . $buyer->bankAffiliate->affiliateNumber
            . ' ' . (count($buyerJuridicAddresses) > 0 ? ('a.j.' .$buyerJuridicAddresses[0]->address) : '')
        );
        $sheet->setCellValue(
            'AT10',
            $carrier->fiscalCode . ' / ' . $carrier->vat
        );
        $sheet->setCellValue(
            'AT12',
            $seller->fiscalCode . ' / ' . $seller->vat
        );
        $sheet->setCellValue(
            'AT14',
            $buyer->fiscalCode . ' / ' . $buyer->vat
        );
        $sheet->setCellValue(
            'AM16',
            $invoice->attachedDocument
        );
        $loadingPoint = $invoice->loadingPoint;
        $sheet->setCellValue(
            'G18',
            ($loadingPoint !== null) ? $loadingPoint->address : ''
        );
        $unloadingPoint = $invoice->unloadingPoint;
        $sheet->setCellValue(
            'AB18',
            ($unloadingPoint !== null) ? $unloadingPoint->address : ''
        );
        $approvedByEmployee = $invoice->approvedByEmployee;
        $sheet->setCellValue(
            'K72',
            trim($approvedByEmployee->position . ' ' . $approvedByEmployee->name)
        );
        $processedByEmployee = $invoice->processedByEmployee;
        $sheet->setCellValue(
            'Q75',
            trim($processedByEmployee->position . ' ' . $processedByEmployee->name)
        );
        $sheet->setCellValue('U85', $invoice->recipientName);

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
