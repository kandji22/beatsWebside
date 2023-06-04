<?php

namespace App\Entity;

use App\Repository\InstrumentalsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass=InstrumentalsRepository::class)
 * @Vich\Uploadable
 */
class Instrumentals
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
    private $titre;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fichier_audio;

    /**
     * @Vich\UploadableField(mapping="instrumentals_audio", fileNameProperty="fichier_audio")
     * @Assert\File(maxSize="10M",maxSizeMessage="La taille maximale autorisée est de 10 Mo.")
     */
    private $fichier;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombres_likes;

    /**
     * @ORM\ManyToOne(targetEntity=Albums::class, inversedBy="instrumentals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $album_id;

    /**
     * @ORM\OneToMany(targetEntity=Likes::class, mappedBy="instrumental_id", orphanRemoval=true)
     */
    private $likes;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    public function getFichierAudio(): ?string
    {
        return $this->fichier_audio;
    }

    public function setFichierAudio(string $fichier_audio): self
    {
        $this->fichier_audio = $fichier_audio;
        return $this;
    }

    public function getNombresLikes(): ?int
    {
        return $this->nombres_likes;
    }

    public function setNombresLikes(?int $nombres_likes): self
    {
        $this->nombres_likes = $nombres_likes;

        return $this;
    }

    public function getAlbumId(): ?Albums
    {
        return $this->album_id;
    }

    public function setAlbumId(?Albums $album_id): self
    {
        $this->album_id = $album_id;

        return $this;
    }

    /**
     * @return Collection<int, Likes>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }
    public function hasLike() {
        $bool = false;
        $likes = $this->getLikes()->map(function ($like) {
            return $like->getId();
        });

        if(count($likes) > 0) {
            $bool = true;
        }
        return $bool;
    }
    public function addLike(Likes $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setInstrumentalId($this);
        }

        return $this;
    }

    public function removeLike(Likes $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getInstrumentalId() === $this) {
                $like->setInstrumentalId(null);
            }
        }

        return $this;
    }

    public function getFichier(): ?File
    {
        return $this->fichier;
    }

    public function setFichier(?File $fichier): void
    {
        $this->fichier = $fichier;

        if ($fichier) {
            // Générer un nom de fichier unique
            $this->nomFichier = uniqid().'.'.$fichier->guessExtension();
        }
    }

}
