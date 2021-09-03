<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity()
 * @ORM\Table(name="companies")
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
 * @ApiFilter(
 *     SearchFilter::class,
 *     properties={
 *      "name": "start",
 *      "shortName": "start",
 *      "iban": "start",
 *      "fiscalCode": "start",
 *      "vat": "start"
 *     }
 * )
 */
class Company
{
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
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Groups({"read", "write"})
     */
    public string $vat;

    /**
     * @var BankAffiliate
     * @ORM\ManyToOne(targetEntity="App\Entity\BankAffiliate", inversedBy="companies")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    public BankAffiliate $bankAffiliate;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read"})
     */
    public ?\DateTime $created_at = null;

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