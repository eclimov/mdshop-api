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
            ['name' => 'Moldova Agroindbank', 'slug' => 'maib'],
            ['name' => 'Victoriabank', 'slug' => 'vicb'],
        ];

        foreach ($banksData as $bankData) {
            $bank = new Bank();
            $bank->name = $bankData['name'];
            $bank->slug = $bankData['slug'];
            $manager->persist($bank);
        }

        $manager->flush();
    }
}
