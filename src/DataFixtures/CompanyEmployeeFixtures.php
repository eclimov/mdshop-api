<?php

namespace App\DataFixtures;

use App\Entity\CompanyEmployee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CompanyEmployeeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $companyEmployeesData = [
            [
                'name' => 'Eduard Climov',
                'company' => $this->getReference('company_0'),
                'position' => CompanyEmployee::POSITIONS[0]
            ],
            [
                'name' => 'Vasea Pupkin',
                'company' => $this->getReference('company_0'),
                'position' => CompanyEmployee::POSITIONS[1]
            ],
            [
                'name' => 'Ivan Ivanov',
                'company' => $this->getReference('company_1'),
                'position' => CompanyEmployee::POSITIONS[0]
            ]
        ];

        foreach ($companyEmployeesData as $i => $companyEmployeeData) {
            $companyEmployee = new CompanyEmployee();
            $companyEmployee->name = $companyEmployeeData['name'];
            $companyEmployee->company = $companyEmployeeData['company'];
            $companyEmployee->position = $companyEmployeeData['position'];

            $manager->persist($companyEmployee);
            $this->addReference('companyEmployee_' . $i, $companyEmployee);
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
