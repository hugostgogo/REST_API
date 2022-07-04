<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

use App\Controller\UsersController;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Api\UrlGeneratorInterface;

use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    iri: "http://127.0.0.1:8000/api/users",
    urlGenerationStrategy: UrlGeneratorInterface::ABS_URL,
    security: 'is_granted("ROLE_USER")',
    collectionOperations: [
        'get' => [
            'openapi_context' => [
                'summary' => 'Consulter la liste des utilisateurs inscrits liés à un client',
                'description' => 'Retourne la liste des utilisateurs inscrits liés à un client',
            ],
            'controller' =>  [
                UsersController::class,
                'index'
            ],
            'denormalization_context' => [
                'groups' => ['user:write'],
            ],
            'normalization_context' => [
                'groups' => ['user:read'],
            ],
            'pagination_items_per_page' => 10,
            'pagination_enabled_param' => 'page',
            'pagination_items_per_page_param' => 'itemsPerPage',
            'pagination_maximum_items_per_page' => 100,
        ],
        'post' => [
            'openapi_context' => [
                'summary' => 'Ajouter un nouvel utilisateur lié à un client',
                'description' => 'Ajoute un nouvel utilisateur lié à un client',
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'first_name' => [
                                        'type' => 'string',
                                        'description' => 'Prénom de l\'utilisateur',
                                        'example' => 'John',
                                    ],
                                    'last_name' => [
                                        'type' => 'string',
                                        'description' => 'Nom de l\'utilisateur',
                                        'example' => 'Doe',
                                    ],
                                    'email' => [
                                        'type' => 'string',
                                        'description' => 'Email de l\'utilisateur',
                                        'example' => 'johndoe@example.com',
                                    ],
                                ],
                                'required' => ['first_name', 'last_name', 'email'],
                            ],
                        ],
                    ],
                ],

            ],

            'controller' => [UsersController::class, 'create'],
            'denormalization_context' => [
                'groups' => ['user:write'],
            ],
            'normalization_context' => [
                'groups' => ['user:read'],
            ],
        ],
    ],
    itemOperations: [
        'get' => [
            'openapi_context' => [
                'summary' => 'Consulter un utilisateur inscrit sur le site web',
                'description' => 'Retourne les informations d\'un utilisateur inscrit sur le site web',
            ],
            'controller' =>  [
                UsersController::class,
                'show'
            ],
            'denormalization_context' => [
                'groups' => ['user:write'],
            ],
            'normalization_context' => [
                'groups' => ['user:read'],
            ],
        ],
        'delete' => [
            'openapi_context' => [
                'summary' => 'Supprimer un utilisateur ajouté par un client',
                'description' => 'Supprime un utilisateur ajouté par un client',
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'description' => 'Identifiant de l\'utilisateur',
                        'schema' => [
                            'type' => 'integer',
                        ],
                    ],
                ],

            ],
            'controller' => [
                UsersController::class, 'delete'
            ],
        ],
    ],
)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["user:read"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["user:read", "user:write"])]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["user:read", "user:write"])]
    private $first_name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["user:read", "user:write"])]
    private $last_name;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private $customer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
