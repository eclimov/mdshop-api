<?php

namespace App\DataFixtures;

use App\Entity\Invoice;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class InvoiceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $invoicesData = [
            [
                'orderDate' => new \DateTime('NOW'),
                'deliveryDate' => new \DateTime('tomorrow'),
                'carrier' => $this->getReference('company_0'),
                'seller' => $this->getReference('company_0'),
                'buyer' => $this->getReference('company_1'),
                'attachedDocument' => 'Some attached document text',
                'loadingPoint' => $this->getReference('companyAddress_0'),
                'unloadingPoint' => $this->getReference('companyAddress_1'),
                'approvedByEmployee' => $this->getReference('companyEmployee_0'),
                'processedByEmployee' => $this->getReference('companyEmployee_1'),
                'recipientName' => 'Piotr Petrov',
            ],
        ];

        foreach ($invoicesData as $i => $invoiceData) {
            $invoice = new Invoice();
            $invoice->orderDate = $invoiceData['orderDate'];
            $invoice->deliveryDate = $invoiceData['deliveryDate'];
            $invoice->carrier = $invoiceData['carrier'];
            $invoice->seller = $invoiceData['seller'];
            $invoice->buyer = $invoiceData['buyer'];
            $invoice->attachedDocument = $invoiceData['attachedDocument'];
            $invoice->loadingPoint = $invoiceData['loadingPoint'];
            $invoice->unloadingPoint = $invoiceData['unloadingPoint'];
            $invoice->approvedByEmployee = $invoiceData['approvedByEmployee'];
            $invoice->processedByEmployee = $invoiceData['processedByEmployee'];
            $invoice->recipientName = $invoiceData['recipientName'];

            $manager->persist($invoice);
            $this->addReference('invoice_' . $i, $invoice);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            CompanyFixtures::class,
            CompanyAddressFixtures::class,
            CompanyEmployeeFixtures::class,
        ];
    }
}
