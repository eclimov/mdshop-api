<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CompanyFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $companiesData = [
            [
                'name' => 'Artprezent S.R.L.',
                'fiscalCode' => '1102102',
                'iban' => 'IBAN_121212',
                'shortName' => 'Artprezent',
                'vat' => '3434343434343434',
                'bankAffiliate' => $this->getReference('bankAffiliate_0')
            ],
            [
                'name' => 'Moldtelecom S.R.L.',
                'fiscalCode' => '67969769',
                'iban' => 'IBAN_5656565',
                'shortName' => 'Moldtelecom',
                'vat' => '8989898989898989898',
                'bankAffiliate' => $this->getReference('bankAffiliate_1')
            ]
        ];

        foreach ($companiesData as $i => $companyData) {
            $company = new Company();
            $company->name = $companyData['name'];
            $company->fiscalCode = $companyData['fiscalCode'];
            $company->iban = $companyData['iban'];
            $company->shortName = $companyData['shortName'];
            $company->vat = $companyData['vat'];
            $company->bankAffiliate = $companyData['bankAffiliate'];

            $manager->persist($company);
            $this->addReference('company_' . $i, $company);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            BankAffiliateFixtures::class,
        ];
    }
}
