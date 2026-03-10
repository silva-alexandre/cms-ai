<?php

namespace App\Entity;

use App\Repository\ServicoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServicoRepository::class)]
class Servico
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $nome = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $detalhe = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $preco = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $foto = null;

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

    public function getDetalhe(): ?string
    {
        return $this->detalhe;
    }

    public function setDetalhe(?string $detalhe): static
    {
        $this->detalhe = $detalhe;

        return $this;
    }

    public function getPreco(): ?string
    {
        return $this->preco;
    }

    public function setPreco(?string $preco): static
    {
        $this->preco = $preco;

        return $this;
    }

    public function getFoto(): ?array
    {
        return $this->foto;
    }

    public function setFoto(?array $foto): static
    {
        $this->foto = $foto;

        return $this;
    }
}
