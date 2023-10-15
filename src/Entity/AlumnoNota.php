<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * AlumnoNota
 *
 * @ORM\Table(name="alumno_nota", indexes={@ORM\Index(name="fk_seccion", columns={"seccion"}), @ORM\Index(name="fk_semestre", columns={"semestre"}), @ORM\Index(name="fk_curso", columns={"curso"}), @ORM\Index(name="fk_alumno", columns={"alumno"})})
 * @ORM\Entity
 */
class AlumnoNota
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
     * @ORM\Column(name="nota", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $nota;

    /**
     * @var \Alumno
     *
     * @ORM\ManyToOne(targetEntity="Alumno")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="alumno", referencedColumnName="id")
     * })
     */
    private $alumno;

    /**
     * @var \Semestre
     *
     * @ORM\ManyToOne(targetEntity="Semestre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="semestre", referencedColumnName="id")
     * })
     */
    private $semestre;

    /**
     * @var \Curso
     *
     * @ORM\ManyToOne(targetEntity="Curso")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="curso", referencedColumnName="id")
     * })
     */
    private $curso;

    /**
     * @var \Seccion
     *
     * @ORM\ManyToOne(targetEntity="Seccion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="seccion", referencedColumnName="id")
     * })
     */
    private $seccion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNota(): ?string
    {
        return $this->nota;
    }

    public function setNota(string $nota): static
    {
        $this->nota = $nota;

        return $this;
    }

    public function getAlumno(): ?Alumno
    {
        return $this->alumno;
    }

    public function setAlumno(?Alumno $alumno): static
    {
        $this->alumno = $alumno;

        return $this;
    }

    public function getSemestre(): ?Semestre
    {
        return $this->semestre;
    }

    public function setSemestre(?Semestre $semestre): static
    {
        $this->semestre = $semestre;

        return $this;
    }

    public function getCurso(): ?Curso
    {
        return $this->curso;
    }

    public function setCurso(?Curso $curso): static
    {
        $this->curso = $curso;

        return $this;
    }

    public function getSeccion(): ?Seccion
    {
        return $this->seccion;
    }

    public function setSeccion(?Seccion $seccion): static
    {
        $this->seccion = $seccion;

        return $this;
    }


}
