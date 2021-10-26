<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity()
 * @ORM\Table(name="bank_affiliates")
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
 *      "affiliateNumber": "start"
 *     }
 * )
 */
class BankAffiliate
/*
POST
{
  "affiliateNumber": "qwerty",
  "bank": "/banks/1"
}
 */
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     * @Groups({"read"})
     */
    private ?int $id = null;

    /**
     * @var Bank
     * @ORM\ManyToOne(targetEntity="App\Entity\Bank", inversedBy="affiliates", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @Groups({"write"})
     */
    public Bank $bank;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Groups({"read", "write"})
     */
    public string $affiliateNumber;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Company", mappedBy="bankAffiliate")
     */
    public Collection $companies;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read"})
     */
    public ?\DateTime $created_at = null;

    public function __construct()
    {
        $this->companies = new ArrayCollection();
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
        return $this->bank->name . ' - ' . $this->affiliateNumber;
    }
}
