<?php

namespace App\Entity;

use App\Repository\AlbumsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AlbumsRepository::class)
 */
class Albums
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
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Instrumentals::class, mappedBy="album_id", orphanRemoval=true)
     */
    private $instrumentals;

    public function __construct()
    {
        $this->instrumentals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Instrumentals>
     */
    public function getInstrumentals(): Collection
    {
        return $this->instrumentals;
    }

    public function addInstrumental(Instrumentals $instrumental): self
    {
        if (!$this->instrumentals->contains($instrumental)) {
            $this->instrumentals[] = $instrumental;
            $instrumental->setAlbumId($this);
        }

        return $this;
    }

    public function removeInstrumental(Instrumentals $instrumental): self
    {
        if ($this->instrumentals->removeElement($instrumental)) {
            // set the owning side to null (unless already changed)
            if ($instrumental->getAlbumId() === $this) {
                $instrumental->setAlbumId(null);
            }
        }

        return $this;
    }
}
