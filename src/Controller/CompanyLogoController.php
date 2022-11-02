<?php
namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Entity\Company;
use App\Service\FileManager;

#[AsController]
final class CompanyLogoController extends AbstractController
{
    public function __invoke(
        int $id,
        Request $request,
        FileManager $fileManager,
        EntityManagerInterface $em
    ): Company
    {
        /**
         * @var UploadedFile $uploadedFile
         */
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }
        $maxFileSize = 50000;
        if ($uploadedFile->getSize() > $maxFileSize) {
            throw new FileException('The uploaded file is too big (max allowed size is ' . $maxFileSize . ' bytes).');
        }

        $company = $em->getRepository(Company::class)->find($id);
        // upload the file and save its filename
        $company->logo = $fileManager->upload(
            $uploadedFile,
            $company->name,
            Company::LOGO_PATH
        );
        $em->persist($company);
        $em->flush();

        return $company;
    }
}
