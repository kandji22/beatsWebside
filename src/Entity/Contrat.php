<?php

namespace App\Entity;

use App\Repository\ContratRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContratRepository::class)
 */
class Contrat
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fileContrat;

    /**
     * @ORM\OneToOne(targetEntity=Albums::class, mappedBy="contrat", cascade={"persist", "remove"})
     */
    private $albums;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFileContrat(): ?string
    {
        return $this->fileContrat;
    }

    public function setFileContrat(string $fileContrat): self
    {
        $this->fileContrat = $fileContrat;

        return $this;
    }

    public function getAlbums(): ?Albums
    {
        return $this->albums;
    }

    public function setAlbums(Albums $albums): self
    {

        // set the owning side of the relation if necessary
        if ($albums->getContrat() !== $this) {
            $albums->setContrat($this);
        }

        $this->albums = $albums;

        return $this;
    }
    public function __toString()
    {
        return $this->getName();
    }
}
