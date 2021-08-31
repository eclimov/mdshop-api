<?php

namespace App\DataFixtures;

use App\Entity\Bank;
use App\Entity\BankAffiliate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BankAffiliateFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $bankAffiliatesData = [
            ['affiliateNumber' => 'AGRNMD2X710', 'bankReference' => 'bank_0']
        ];

        foreach ($bankAffiliatesData as $i => $bankAffiliateData) {
            $bankAffiliate = new BankAffiliate();
            $bankAffiliate->affiliateNumber = $bankAffiliateData['affiliateNumber'];
            $bankAffiliate->bank = $this->getReference($bankAffiliateData['bankReference']);

            $manager->persist($bankAffiliate);
            $this->addReference('bankAffiliate_' . $i, $bankAffiliate);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            BankFixtures::class,
        ];
    }
}