<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\EntityListeners;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\CompanyLogoController;

/**
 * @ORM\Entity()
 * @EntityListeners({"App\Listeners\CompanyListener"})
 * @ORM\Table(name="companies")
 * @ORM\HasLifecycleCallbacks()
 */
// #[EntityListeners([CompanyListener::class])] // TODO: check why this declaration doesn't work
#[ApiResource(
    itemOperations: [
        'get',
        'patch',
        'delete',
        'put',
        'add_logo' => [
            'method' => 'POST',
            'path' => '/companies/{id}/logo',
            'requirements' => ['id' => '\d+'],
            'controller' => CompanyLogoController::class,
            'deserialize' => false
        ],
    ],
    denormalizationContext: ["groups" => ["write"]],
    normalizationContext: [
        "skip_null_values" => false, // https://stackoverflow.com/questions/59314728/allowing-null-value-in-json-with-api-platform#59856013
        "groups" => ["read"]
    ]
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'name' => 'start',
        'shortName' => 'start',
        'iban' => 'start',
        'fiscalCode' => 'start',
        'vat' => 'start'
    ]
)]
class Company
{
    public const LOGO_PATH = "company/logos";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     * @Groups({"read"})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Groups({"read", "write"})
     */
    public string $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Groups({"read", "write"})
     */
    public string $shortName;

    /**
     * @ORM\Column(name="logo", type="string", nullable=true)
     * @Groups({"read"})
     * @ApiProperty(
     *   iri="https://schema.org/image",
     *   attributes={
     *     "openapi_context"={
     *       "type"="string",
     *     }
     *   }
     * )
     */
    public ?string $logo = null;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Groups({"read", "write"})
     */
    public string $iban;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Groups({"read", "write"})
     */
    public string $fiscalCode;

    /**
     * @ORM\Column(type="string", length=255, unique=false)
     * @Assert\NotBlank()
     * @Groups({"read", "write"})
     */
    public string $vat;

    /**
     * @var BankAffiliate|null
     * @ORM\ManyToOne(targetEntity="App\Entity\BankAffiliate", inversedBy="companies", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * @Groups({"read", "write"})
     */
    #[ApiSubresource (
        maxDepth: 1
    )]
    public ?BankAffiliate $bankAffiliate;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\CompanyAddress", mappedBy="company", cascade={"remove"})
     */
    #[ApiSubresource (
        maxDepth: 1
    )]
    public Collection $addresses;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\CompanyEmployee", mappedBy="company", cascade={"remove"})
     */
    #[ApiSubresource (
        maxDepth: 1
    )]
    public Collection $employees;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read"})
     */
    public ?\DateTime $created_at = null;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="company", cascade={"remove"})
     */
    #[ApiSubresource (
        maxDepth: 1
    )]
    public Collection $users;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Prepersist gets triggered on Insert
     * @ORM\PrePersist
     */
    public function updatedTimestamps(): void
    {
        if ($this->created_at == null) {
            $this->created_at = new \DateTime('now');
        }
    }

    public function __toString()
    {
        return $this->name;
    }
}
