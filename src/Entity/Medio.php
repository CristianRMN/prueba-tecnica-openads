<?php

namespace App\Entity;

use App\Repository\MedioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedioRepository::class)]
class Medio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $dominio = null;

    #[ORM\Column(length: 100)]
    private ?string $categoria = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $tematicas_delicadas = null;

    #[ORM\Column]
    private ?int $num_enlaces_permitidos = null;

    #[ORM\Column(type: 'json')]
    private array $tiposEnlacePermitidos = [];

    #[ORM\Column]
    private ?bool $publica_portada = null;

    #[ORM\Column]
    private ?bool $publica_categorias = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $tematicas_no_aceptadas = null;

    #[ORM\Column]
    private ?bool $indica_patrocinado = null;

    #[ORM\Column]
    private ?int $trafico_mes = null;

    #[ORM\Column(nullable: true)]
    private ?int $da = null;

    #[ORM\Column(nullable: true)]
    private ?int $dr = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    /**
     * @var Collection<int, Contenido>
     */
    #[ORM\OneToMany(targetEntity: Contenido::class, mappedBy: 'medio', orphanRemoval: true)]
    private Collection $contenidos;

    /**
     * @var Collection<int, Tarifa>
     */
    #[ORM\OneToMany(targetEntity: Tarifa::class, mappedBy: 'medio', orphanRemoval: true)]
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

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDominio(): ?string
    {
        return $this->dominio;
    }

    public function setDominio(string $dominio): static
    {
        $this->dominio = $dominio;

        return $this;
    }

    public function getCategoria(): ?string
    {
        return $this->categoria;
    }

    public function setCategoria(string $categoria): static
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getTematicasDelicadas(): ?string
    {
        return $this->tematicas_delicadas;
    }

    public function setTematicasDelicadas(?string $tematicas_delicadas): static
    {
        $this->tematicas_delicadas = $tematicas_delicadas;

        return $this;
    }

    public function getNumEnlacesPermitidos(): ?int
    {
        return $this->num_enlaces_permitidos;
    }

    public function setNumEnlacesPermitidos(int $num_enlaces_permitidos): static
    {
        $this->num_enlaces_permitidos = $num_enlaces_permitidos;

        return $this;
    }


    public function getTiposEnlacePermitidos(): array
    {
        return $this->tiposEnlacePermitidos;
    }


    public function setTiposEnlacePermitidos(array $tipos): static
    {
        $this->tiposEnlacePermitidos = $tipos;
        return $this;
    }



    public function isPublicaPortada(): ?bool
    {
        return $this->publica_portada;
    }

    public function setPublicaPortada(bool $publica_portada): static
    {
        $this->publica_portada = $publica_portada;

        return $this;
    }

    public function isPublicaCategorias(): ?bool
    {
        return $this->publica_categorias;
    }

    public function setPublicaCategorias(bool $publica_categorias): static
    {
        $this->publica_categorias = $publica_categorias;

        return $this;
    }

    public function getTematicasNoAceptadas(): ?string
    {
        return $this->tematicas_no_aceptadas;
    }

    public function setTematicasNoAceptadas(?string $tematicas_no_aceptadas): static
    {
        $this->tematicas_no_aceptadas = $tematicas_no_aceptadas;

        return $this;
    }

    public function isIndicaPatrocinado(): ?bool
    {
        return $this->indica_patrocinado;
    }

    public function setIndicaPatrocinado(bool $indica_patrocinado): static
    {
        $this->indica_patrocinado = $indica_patrocinado;

        return $this;
    }

    public function getTraficoMes(): ?int
    {
        return $this->trafico_mes;
    }

    public function setTraficoMes(int $trafico_mes): static
    {
        $this->trafico_mes = $trafico_mes;

        return $this;
    }

    public function getDa(): ?int
    {
        return $this->da;
    }

    public function setDa(?int $da): static
    {
        $this->da = $da;

        return $this;
    }

    public function getDr(): ?int
    {
        return $this->dr;
    }

    public function setDr(?int $dr): static
    {
        $this->dr = $dr;

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
            $contenido->setMedio($this);
        }

        return $this;
    }

    public function removeContenido(Contenido $contenido): static
    {
        if ($this->contenidos->removeElement($contenido)) {
            // set the owning side to null (unless already changed)
            if ($contenido->getMedio() === $this) {
                $contenido->setMedio(null);
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
            $tarifa->setMedioId($this);
        }

        return $this;
    }

    public function removeTarifa(Tarifa $tarifa): static
    {
        if ($this->tarifas->removeElement($tarifa)) {
            // set the owning side to null (unless already changed)
            if ($tarifa->getMedioId() === $this) {
                $tarifa->setMedioId(null);
            }
        }

        return $this;
    }

    public function toArray(): array
{
    return [
        'id' => $this->id,
        'nombre' => $this->nombre,
        'dominio' => $this->dominio,
        'categoria' => $this->categoria,
        'tematicas_delicadas' => $this->tematicas_delicadas,
        'num_enlaces_permitidos' => $this->num_enlaces_permitidos,
        'tipos_enlace_permitidos' => $this->tiposEnlacePermitidos,
        'publica_portada' => $this->publica_portada,
        'publica_categorias' => $this->publica_categorias,
        'tematicas_no_aceptadas' => $this->tematicas_no_aceptadas,
        'indica_patrocinado' => $this->indica_patrocinado,
        'trafico_mes' => $this->trafico_mes,
        'da' => $this->da,
        'dr' => $this->dr,
        'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        'num_contenidos' => $this->contenidos->count(),
        'tarifas' => array_map(function($tarifa){
            return [
                'id' => $tarifa->getId(),
                'proveedor' => $tarifa->getProovedorId()?->getEmpresa(),
                'precio' => $tarifa->getPrecio(),
                'moneda' => $tarifa->getMoneda(),
                'vigente_desde' => $tarifa->getVigenteDesde()?->format('Y-m-d'),
                'vigente_hasta' => $tarifa->getVigenteHasta()?->format('Y-m-d'),
            ];
        }, $this->tarifas->toArray()),
    ];
}

}
