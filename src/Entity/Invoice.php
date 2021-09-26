<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\GenerateInvoiceDocument;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoices")
 * @ORM\HasLifecycleCallbacks()
 */
#[ApiResource(
    collectionOperations: [
        'get',
        'create_invoice' => [
            'method'          => 'post',
            'path'            => '/invoices',
            'openapi_context' => [
                'summary'     => 'Create an Invoice resource',
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type'       => 'object',
                                'properties' =>
                                    [
                                        'orderDate' => ['type' => 'datetime'],
                                        'deliveryDate' => ['type' => 'datetime'],
                                        'carrier' => ['type' => 'string'],
                                        'seller' => ['type' => 'string'],
                                        'buyer' => ['type' => 'string'],
                                        'attachedDocument' => ['type' => 'string'],
                                        'loadingPoint' => ['type' => 'string'],
                                        'unloadingPoint' => ['type' => 'string'],
                                        'approvedByEmployee' => ['type' => 'string'],
                                        'processedByEmployee' => ['type' => 'string'],
                                        'recipientName' => ['type' => 'string'],
                                    ],
                            ],
                            'example' => [
                                'orderDate' => '2021-09-12T16:10:37.959Z',
                                'deliveryDate' => '2021-09-12T16:10:37.959Z',
                                'carrier' => '/companies/2',
                                'seller' => '/companies/1',
                                'buyer' => '/companies/2',
                                'attachedDocument' => 'Some note text',
                                'loadingPoint' => '/company_addresses/1',
                                'unloadingPoint' => '/company_addresses/2',
                                'approvedByEmployee' => '/company_employees/1',
                                'processedByEmployee' => '/company_employees/2',
                                'recipientName' => 'Name of the recipient',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    itemOperations: [
        'get',
        'generate_invoice_document' => [
            'method' => 'post',
            'status' => 200,
            'path' => '/invoices/{id}/generate',
            'requirements' => ['id' => '\d+'],
            'read' => false, // https://api-platform.com/docs/core/controllers/#retrieving-the-entity
            'deserialize' => false,
            'validate' => false,
            'controller' => GenerateInvoiceDocument::class,
            'openapi_context' => [
                'summary'     => 'Generate an Invoice document',
                'parameters' => [
                    [
                        "name" => "id",
                        "in" => "path",
                        "description" => "Invoice id",
                        "required" => true,
                        "schema" => [
                            "type" => "number"
                        ]
                    ]
                ],
                'requestBody' => [
                    'content' => []
                ]
            ],
        ]
    ],
    denormalizationContext: ["groups" => ["write"]],
    normalizationContext: ["groups" => ["read"]]
)]
class Invoice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     * @Groups({"read"})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read", "write"})
     */
    public ?\DateTime $orderDate;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read", "write"})
     */
    public ?\DateTime $deliveryDate;

    /**
     * @var Company
     * @ORM\ManyToOne(targetEntity="App\Entity\Company")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * @Groups({"read", "write"})
     */
    public Company $carrier;

    /**
     * @var Company
     * @ORM\ManyToOne(targetEntity="App\Entity\Company")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * @Groups({"read", "write"})
     */
    public Company $seller;

    /**
     * @var Company
     * @ORM\ManyToOne(targetEntity="App\Entity\Company")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * @Groups({"read", "write"})
     */
    public Company $buyer;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"read", "write"})
     */
    public string $attachedDocument;

    /**
     * @var CompanyAddress
     * @ORM\ManyToOne(targetEntity="App\Entity\CompanyAddress")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * @Groups({"read", "write"})
     */
    public CompanyAddress $loadingPoint;

    /**
     * @var CompanyAddress
     * @ORM\ManyToOne(targetEntity="App\Entity\CompanyAddress")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * @Groups({"read", "write"})
     */
    public CompanyAddress $unloadingPoint;

    /**
     * @var CompanyEmployee
     * @ORM\ManyToOne(targetEntity="App\Entity\CompanyEmployee")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * @Groups({"read", "write"})
     */
    public CompanyEmployee $approvedByEmployee;

    /**
     * @var CompanyEmployee
     * @ORM\ManyToOne(targetEntity="App\Entity\CompanyEmployee")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * @Groups({"read", "write"})
     */
    public CompanyEmployee $processedByEmployee;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"read", "write"})
     */
    public string $recipientName;

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
        return 'Invoice #' . $this->id;
    }
}
