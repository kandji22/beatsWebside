<?php

namespace App\Entity;

use App\Repository\AlbumsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


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
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="albumfile", fileNameProperty="image")
     * @var File
     */
    private $albumFile;

    /**
     * @ORM\OneToMany(targetEntity=Instrumentals::class, mappedBy="album_id", orphanRemoval=true)
     */
    private $instrumentals;

    /**
     * @ORM\Column(type="float")
     */
    private $price;


    /**
     * @ORM\ManyToOne(targetEntity=Contrat::class, inversedBy="albums")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contrat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="albums")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdat;

    public function __construct()
    {
        $this->instrumentals = new ArrayCollection();
        $this->status = 'false';
        // Obtention de la date et de l'heure actuelles
        $currentDateTime = new \DateTime();
        // Utilisation de la date et de l'heure actuelles
        $this->setCreatedat($currentDateTime);
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function setImage(?string $image): self
    {
        $this->image = $image ?? 'default.jpg';

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
            $instrumental->setAlbum($this);
        }

        return $this;
    }

    public function removeInstrumental(Instrumentals $instrumental): self
    {
        //remove file instrument
        if ($this->instrumentals->removeElement($instrumental)) {
            // set the owning side to null (unless already changed)
            if ($instrumental->getAlbum() === $this) {
                $instrumental->setAlbum(null);
            }
        }

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }

    public function setContrat(?Contrat $contrat): self
    {
        $this->contrat = $contrat;

        return $this;
    }


    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
    public function __toString(): string
    {
        $status = $this->getStatus() == "1"?"Vendu":"Disponible";
        $idAlbum = $this->getId();
        return $this->getTitle().','.$status;
    }

    public function getCreatedat(): ?\DateTimeInterface
    {
        return $this->createdat;
    }

    public function setCreatedat(?\DateTimeInterface $createdat): self
    {
        $this->createdat = $createdat;

        return $this;
    }

    public function getAlbumFile(): ?File
    {
        return $this->albumFile;
    }

    public function setAlbumFile(?File $albumFile): void
    {
        $this->albumFile = $albumFile;

        if ($albumFile) {
            // Générer un nom de fichier unique
            $this->image = 'audio_' . time() . '.' . $albumFile->guessExtension();
        }
    }
}
