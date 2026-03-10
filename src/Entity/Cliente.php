<?php

namespace App\Entity;

use App\Repository\ClienteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClienteRepository::class)]
class Cliente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $nome = null;

    #[ORM\Column(length: 35, nullable: true)]
    private ?string $contato = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(?string $nome): static
    {
        $this->nome = $nome;

        return $this;
    }

    public function getContato(): ?string
    {
        return $this->contato;
    }

    public function setContato(?string $contato): static
    {
        $this->contato = $contato;

        return $this;
    }
}
