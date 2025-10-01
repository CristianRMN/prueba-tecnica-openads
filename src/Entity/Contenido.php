<?php

namespace App\Entity;

use App\Repository\ContenidoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Model\EstadoCompra;
use App\Model\PagoProovedor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: ContenidoRepository::class)]
class Contenido
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'contenidos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Medio $medio = null;

    #[ORM\ManyToOne(inversedBy: 'contenidos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Proovedor $proovedor = null;


    #[ORM\Column(length: 255)]
    private ?string $titulo = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $cuerpo = null;

    #[ORM\Column(length: 255)]
    private ?string $tipo_contenido = null;

    #[ORM\Column]
    private ?int $num_enlaces = null;

    #[ORM\Column]
    private ?int $longitud_palabras = null;

    #[ORM\Column(length: 255)]
    private ?string $categoria_publicar = null;


    #[ORM\Column(type: 'string', enumType: EstadoCompra::class)]
    private EstadoCompra $estado_compra;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url_publicacion = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $fecha_publicacion = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $precio_aplicado = null;

    #[ORM\Column(length: 3)]
    private ?string $moneda = null;

    #[ORM\Column(type: 'string', enumType: PagoProovedor::class)]
    private PagoProovedor $pago_proveedor;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $fecha_pago = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

  
    #[ORM\OneToMany(targetEntity: Enlace::class, mappedBy: 'contenido', orphanRemoval: true)]
    private Collection $enlaces;

    public function __construct()
    {
        $this->enlaces = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMedio(): ?Medio
    {
        return $this->medio;
    }

    public function setMedio(?Medio $medio): static
    {
        $this->medio = $medio;

        return $this;
    }

    public function getProovedor(): ?Proovedor
    {
        return $this->proovedor;
    }

    public function setProovedor(?Proovedor $proovedor): static
    {
        $this->proovedor = $proovedor;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCuerpo(): ?string
    {
        return $this->cuerpo;
    }

    public function setCuerpo(string $cuerpo): static
    {
        $this->cuerpo = $cuerpo;

        return $this;
    }

    public function getTipoContenido(): ?string
    {
        return $this->tipo_contenido;
    }

    public function setTipoContenido(string $tipo_contenido): static
    {
        $this->tipo_contenido = $tipo_contenido;

        return $this;
    }

    public function getNumEnlaces(): ?int
    {
        return $this->num_enlaces;
    }

    public function setNumEnlaces(int $num_enlaces): static
    {
        $this->num_enlaces = $num_enlaces;

        return $this;
    }

    public function getLongitudPalabras(): ?int
    {
        return $this->longitud_palabras;
    }

    public function setLongitudPalabras(int $longitud_palabras): static
    {
        $this->longitud_palabras = $longitud_palabras;

        return $this;
    }

    public function getCategoriaPublicar(): ?string
    {
        return $this->categoria_publicar;
    }

    public function setCategoriaPublicar(string $categoria_publicar): static
    {
        $this->categoria_publicar = $categoria_publicar;

        return $this;
    }

    public function getEstadoCompra(): EstadoCompra
    {
        return $this->estado_compra;
    }

    public function setEstadoCompra(EstadoCompra $estado_compra): static
    {
        $this->estado_compra = $estado_compra;
        return $this;
    }

    public function getUrlPublicacion(): ?string
    {
        return $this->url_publicacion;
    }

    public function setUrlPublicacion(?string $url_publicacion): static
    {
        $this->url_publicacion = $url_publicacion;

        return $this;
    }

    public function getFechaPublicacion(): ?\DateTimeImmutable
    {
        return $this->fecha_publicacion;
    }

    public function setFechaPublicacion(?\DateTimeImmutable $fecha_publicacion): static
    {
        $this->fecha_publicacion = $fecha_publicacion;

        return $this;
    }

    public function getPrecioAplicado(): ?string
    {
        return $this->precio_aplicado;
    }

    public function setPrecioAplicado(string $precio_aplicado): static
    {
        $this->precio_aplicado = $precio_aplicado;

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

    public function getPagoProveedor(): PagoProovedor
    {
        return $this->pago_proveedor;
    }

    public function setPagoProveedor(PagoProovedor $pago_proveedor): static
    {
        $this->pago_proveedor = $pago_proveedor;
        return $this;
    }

    public function getFechaPago(): ?\DateTimeImmutable
    {
        return $this->fecha_pago;
    }

    public function setFechaPago(?\DateTimeImmutable $fecha_pago): static
    {
        $this->fecha_pago = $fecha_pago;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }


    public function getEnlaces(): Collection
    {
        return $this->enlaces;
    }

    public function addEnlace(Enlace $enlace): static
    {
        if (!$this->enlaces->contains($enlace)) {
            $this->enlaces[] = $enlace;
            $enlace->setContenido($this);
        }

        return $this;
    }

    public function removeEnlace(Enlace $enlace): static
    {
        if ($this->enlaces->removeElement($enlace)) {
            if ($enlace->getContenido() === $this) {
                $enlace->setContenido(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'descripcion' => $this->description,
            'tipo_contenido' => $this->tipo_contenido,
            'estado_compra' => $this->estado_compra->value,
            'precio_aplicado' => $this->precio_aplicado,
            'moneda' => $this->moneda,
            'pago_proveedor' => $this->pago_proveedor->value,
            'medio' => [
                'id' => $this->medio->getId(),
                'nombre' => $this->medio->getNombre(),
                'dominio' => $this->medio->getDominio(),
            ],
            'proveedor' => [
                'id' => $this->proovedor->getId(),
                'empresa' => $this->proovedor->getEmpresa(),
            ],
            'enlaces' => array_map(function($enlace) {
                return [
                    'url_destino' => $enlace->getUrlDestino(),
                    'anchor_text' => $enlace->getAnchorText(),
                    'atributo' => $enlace->getAtributo()->value
                ];
            }, $this->enlaces->toArray()),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }



}
