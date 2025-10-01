<?php

namespace App\Entity;

use App\Repository\EnlaceRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Model\TipoEnlace;


#[ORM\Entity(repositoryClass: EnlaceRepository::class)]
class Enlace
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Contenido::class, inversedBy: 'enlaces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contenido $contenido= null;

    #[ORM\Column(length: 255)]
    private ?string $url_destino = null;

    #[ORM\Column(length: 255)]
    private ?string $anchor_text = null;

    #[ORM\Column(type: 'string', enumType: TipoEnlace::class)]
    private TipoEnlace $atributo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenido(): ?Contenido
    {
        return $this->contenido;
    }

    public function setContenido(?Contenido $contenido): static
    {
        $this->contenido = $contenido;
        return $this;
    }

    public function getUrlDestino(): ?string
    {
        return $this->url_destino;
    }

    public function setUrlDestino(string $url_destino): static
    {
        $this->url_destino = $url_destino;

        return $this;
    }

    public function getAnchorText(): ?string
    {
        return $this->anchor_text;
    }

    public function setAnchorText(string $anchor_text): static
    {
        $this->anchor_text = $anchor_text;

        return $this;
    }

    public function getAtributo(): TipoEnlace
    {
        return $this->atributo;
    }

    public function setAtributo(TipoEnlace $atributo): static
    {
        $this->atributo = $atributo;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'contenido_id' => $this->contenido?->getId(), 
            'url_destino' => $this->url_destino,
            'anchor_text' => $this->anchor_text,
            'atributo' => $this->atributo->value,
        ];
    }

}
