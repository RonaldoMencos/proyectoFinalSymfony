<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Carrera
 *
 * @ORM\Table(name="carrera")
 * @ORM\Entity
 */
class Carrera
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=150, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="jornada", type="string", length=60, nullable=false)
     */
    private $jornada;

    /**
     * @var string
     *
     * @ORM\Column(name="ubicacion", type="string", length=150, nullable=false)
     */
    private $ubicacion;

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

    public function getJornada(): ?string
    {
        return $this->jornada;
    }

    public function setJornada(string $jornada): static
    {
        $this->jornada = $jornada;

        return $this;
    }

    public function getUbicacion(): ?string
    {
        return $this->ubicacion;
    }

    public function setUbicacion(string $ubicacion): static
    {
        $this->ubicacion = $ubicacion;

        return $this;
    }


}
