<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ORM\Entity()
 * @ORM\Table(name="company_addresses")
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource(
 *     normalizationContext={"groups" = {"read"}},
 *     denormalizationContext={"groups" = {"write"}},
 *     itemOperations={
 *      "get",
 *      "patch",
 *      "delete",
 *      "put"
 *     }
 * )
 */
#[ApiFilter(SearchFilter::class, properties: ["address" => "partial"])]
#[ApiFilter(BooleanFilter::class, properties: ["juridic"])]
class CompanyAddress
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     * @Groups({"read"})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"read", "write"})
     */
    public string $address;
    
    /**
     * @var Company
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="addresses", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @Groups({"write"})
     */
    public Company $company;

    /**
     * @ORM\Column(name="is_juridic", type="boolean", nullable=false)
     * @Groups({"read", "write"})
     */
    public bool $juridic;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read"})
     */
    public ?\DateTime $created_at = null;

    public function __construct()
    {
        $this->juridic = false;  // default value
    }

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
        return $this->company->name . ' - ' . $this->address;
    }
}
