<?php

namespace App\Entity;

use App\Repository\OrcamentoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrcamentoRepository::class)]
class Orcamento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 120, nullable: true)]
    #[Assert\NotBlank(message: "O título não pode estar em branco.")]
    #[Assert\Length(max: 120)]
    private ?string $titulo = null;

    #[ORM\ManyToOne]
    #[Assert\NotNull(message: "O serviço deve ser selecionado.")]
    private ?Servico $id_servico = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Positive(message: "A quantidade deve ser um valor positivo.")]
    private ?string $qtd = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $valor_total = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $data = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(?string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getIdServico(): ?Servico
    {
        return $this->id_servico;
    }

    public function setIdServico(?Servico $id_servico): static
    {
        $this->id_servico = $id_servico;

        return $this;
    }

    public function getQtd(): ?string
    {
        return $this->qtd;
    }

    public function setQtd(?string $qtd): static
    {
        $this->qtd = $qtd;

        return $this;
    }

    public function getValorTotal(): ?string
    {
        return $this->valor_total;
    }

    public function setValorTotal(?string $valor_total): static
    {
        $this->valor_total = $valor_total;

        return $this;
    }

    public function getData(): ?\DateTime
    {
        return $this->data;
    }

    public function setData(?\DateTime $data): static
    {
        $this->data = $data;

        return $this;
    }
}
