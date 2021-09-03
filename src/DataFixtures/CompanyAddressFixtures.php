<?php

namespace App\DataFixtures;

use App\Entity\CompanyAddress;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CompanyAddressFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $companyAddressesData = [
            [
                'address' => 'Chisinau, str. Mircea cel Batran 1/2',
                'company' => $this->getReference('company_0'),
                'juridic' => true
            ],
            [
                'address' => 'Chisinau, str. Alecu Russo 2/3',
                'company' => $this->getReference('company_0'),
                'juridic' => false
            ]
        ];

        foreach ($companyAddressesData as $i => $companyAddressData) {
            $companyAddress = new CompanyAddress();
            $companyAddress->address = $companyAddressData['address'];
            $companyAddress->company = $companyAddressData['company'];
            $companyAddress->juridic = $companyAddressData['juridic'];

            $manager->persist($companyAddress);
            $this->addReference('companyAddress_' . $i, $companyAddress);
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
        ];
    }
}
