<?php

namespace App\DataFixtures;

use App\Entity\Bank;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BankFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $banksData = [
            ['name' => 'Moldova Agroindbank'],
            ['name' => 'Victoriabank'],
        ];

        foreach ($banksData as $i => $bankData) {
            $bank = new Bank();
            $bank->name = $bankData['name'];
            $manager->persist($bank);
            $this->addReference('bank_' . $i, $bank);
        }

        $manager->flush();
    }
}
