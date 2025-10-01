<?php

namespace App\Entity;

use App\Repository\ProovedorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProovedorRepository::class)]
class Proovedor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $contacto = null;

    #[ORM\Column(length: 255)]
    private ?string $empresa = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 20)]
    private ?string $telefono = null;

    #[ORM\Column(length: 255)]
    private ?string $direccion = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $cp = null;

    #[ORM\Column(length: 150)]
    private ?string $ciudad = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $provincia = null;

    #[ORM\Column(length: 200)]
    private ?string $pais = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    /**
     * @var Collection<int, Contenido>
     */
    #[ORM\OneToMany(targetEntity: Contenido::class, mappedBy: 'proovedor', orphanRemoval: true)]
    private Collection $contenidos;

    /**
     * @var Collection<int, Tarifa>
     */
    #[ORM\OneToMany(targetEntity: Tarifa::class, mappedBy: 'proovedor', orphanRemoval: true)]
    private Collection $tarifas;


    public function __construct()
    {
        $this->contenidos = new ArrayCollection();
        $this->tarifas = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContacto(): ?string
    {
        return $this->contacto;
    }

    public function setContacto(string $contacto): static
    {
        $this->contacto = $contacto;

        return $this;
    }

    public function getEmpresa(): ?string
    {
        return $this->empresa;
    }

    public function setEmpresa(string $empresa): static
    {
        $this->empresa = $empresa;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): static
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): static
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(?string $cp): static
    {
        $this->cp = $cp;

        return $this;
    }

    public function getCiudad(): ?string
    {
        return $this->ciudad;
    }

    public function setCiudad(string $ciudad): static
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    public function getProvincia(): ?string
    {
        return $this->provincia;
    }

    public function setProvincia(?string $provincia): static
    {
        $this->provincia = $provincia;

        return $this;
    }

    public function getPais(): ?string
    {
        return $this->pais;
    }

    public function setPais(string $pais): static
    {
        $this->pais = $pais;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, Contenido>
     */
    public function getContenidos(): Collection
    {
        return $this->contenidos;
    }

    public function addContenido(Contenido $contenido): static
    {
        if (!$this->contenidos->contains($contenido)) {
            $this->contenidos->add($contenido);
            $contenido->setProovedor($this);
        }

        return $this;
    }

    public function removeContenido(Contenido $contenido): static
    {
        if ($this->contenidos->removeElement($contenido)) {
            // set the owning side to null (unless already changed)
            if ($contenido->getProovedor() === $this) {
                $contenido->setProovedor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tarifa>
     */
    public function getTarifas(): Collection
    {
        return $this->tarifas;
    }

    public function addTarifa(Tarifa $tarifa): static
    {
        if (!$this->tarifas->contains($tarifa)) {
            $this->tarifas->add($tarifa);
            $tarifa->setProovedorId($this);
        }

        return $this;
    }

    public function removeTarifa(Tarifa $tarifa): static
    {
        if ($this->tarifas->removeElement($tarifa)) {
            // set the owning side to null (unless already changed)
            if ($tarifa->getProovedorId() === $this) {
                $tarifa->setProovedorId(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return[
            'id' => $this->id,
            'contacto' => $this->contacto,
            'empresa' => $this->empresa,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'cp' => $this->cp,
            'ciudad' => $this->ciudad,
            'provincia' => $this->provincia,
            'pais' => $this->pais,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'contenidos' => array_map(function($content){
                return[
                    'id' => $content->getId(),
                    'titulo' => $content->getTitulo(),
                    'description' => $content->getDescription(),
                    'pago_proveedor' => $content->getPagoProveedor()
                ];
            }, $this->contenidos->toArray())

        ];
    }

    
   
}
