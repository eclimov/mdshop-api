<?php

namespace App\Listeners;

use App\Entity\Company;
use App\Service\FileManager;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class CompanyListener
{
    private FileManager $fileManager;

    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    // TODO: check why #[PreRemove] property approach doesn't work
    // https://www.doctrine-project.org/projects/doctrine-orm/en/2.13/reference/events.html#entity-listeners-class
    public function preRemove(Company $company, LifecycleEventArgs $event)
    {
        $this->fileManager->remove(
            Company::LOGO_PATH.'/'.$company->logo
        );
    }
}
