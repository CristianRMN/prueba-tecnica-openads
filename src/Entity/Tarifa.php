<?php

namespace App\Entity;

use App\Repository\TarifaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TarifaRepository::class)]
class Tarifa
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tarifas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Proovedor $proovedor = null;

    #[ORM\ManyToOne(inversedBy: 'tarifas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Medio $medio = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $precio = null;

    #[ORM\Column(length: 3)]
    private ?string $moneda = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $vigente_desde = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $vigente_hasta = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProovedorId(): ?Proovedor
    {
        return $this->proovedor;
    }

    public function setProovedorId(?Proovedor $proovedor): static
    {
        $this->proovedor = $proovedor;

        return $this;
    }

    public function getMedioId(): ?Medio
    {
        return $this->medio;
    }

    public function setMedioId(?Medio $medio): static
    {
        $this->medio = $medio;

        return $this;
    }

    public function getPrecio(): ?string
    {
        return $this->precio;
    }

    public function setPrecio(string $precio): static
    {
        $this->precio = $precio;

        return $this;
    }

    public function getMoneda(): ?string
    {
        return $this->moneda;
    }

    public function setMoneda(string $moneda): static
    {
        $this->moneda = $moneda;

        return $this;
    }

    public function getVigenteDesde(): ?\DateTime
    {
        return $this->vigente_desde;
    }

    public function setVigenteDesde(\DateTime $vigente_desde): static
    {
        $this->vigente_desde = $vigente_desde;

        return $this;
    }

    public function getVigenteHasta(): ?\DateTime
    {
        return $this->vigente_hasta;
    }

    public function setVigenteHasta(?\DateTime $vigente_hasta): static
    {
        $this->vigente_hasta = $vigente_hasta;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'proveedor' => $this->proovedor?->getEmpresa(),  
            'medio' => $this->medio?->getNombre(),           
            'precio' => $this->precio,
            'moneda' => $this->moneda,
            'vigente_desde' => $this->vigente_desde?->format('Y-m-d'),
            'vigente_hasta' => $this->vigente_hasta?->format('Y-m-d'),
        ];
    }

}
