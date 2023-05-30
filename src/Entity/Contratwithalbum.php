<?php

namespace App\Entity;

use App\Repository\ContratwithalbumRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContratwithalbumRepository::class)
 */
class Contratwithalbum
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $file;

    /**
     * @ORM\OneToOne(targetEntity=Albums::class, cascade={"persist", "remove"})
     */
    private $album;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getAlbum(): ?Albums
    {
        return $this->album;
    }

    public function setAlbum(?Albums $album): self
    {
        $this->album = $album;

        return $this;
    }
}
