<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'openapi_context' => [
                'summary' => 'Consulter la liste des utilisateurs inscrits liés à un client',
            ]
        ],
        'post' => [
            'openapi_context' => [
                'summary' => 'Ajouter un nouvel utilisateur lié à un client',
            ]
        ],
    ],
    itemOperations: [
        'get' => [
            'openapi_context' => [
                'summary' => 'Consulter un utilisateur inscrit sur le site web',
            ]
        ],
        'delete' => [
            'openapi_context' => [
                'summary' => 'Supprimer un utilisateur ajouté par un client',
            ]
        ],
    ],
)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    private $first_name;

    #[ORM\Column(type: 'string', length: 255)]
    private $last_name;

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
}
