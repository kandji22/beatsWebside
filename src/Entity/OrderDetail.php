<?php

namespace App\Entity;

use App\Repository\OrderDetailRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity(repositoryClass=OrderDetailRepository::class)
 */
class OrderDetail
{
    public function __construct()
    {
        $this->idAlbum = new ArrayCollection();
        // Obtention de la date et de l'heure actuelles
        $currentDateTime = new \DateTime();
        // Utilisation de la date et de l'heure actuelles
       $this->setCreatedat($currentDateTime);

    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fullnameuser;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdat;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombreAlbum;

    /**
     * @ORM\OneToMany(targetEntity=Albums::class, mappedBy="orderDetail")
     */
    private $idAlbum;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stripesession;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullnameuser(): ?string
    {
        return $this->fullnameuser;
    }

    public function setFullnameuser(string $fullnameuser): self
    {
        $this->fullnameuser = $fullnameuser;

        return $this;
    }

    public function getCreatedat(): ?\DateTimeInterface
    {
        return $this->createdat;
    }

    public function setCreatedat(\DateTimeInterface $createdat): self
    {
        $this->createdat = $createdat;

        return $this;
    }

    public function getNombreAlbum(): ?int
    {
        return $this->nombreAlbum;
    }

    public function setNombreAlbum(int $nombreAlbum): self
    {
        $this->nombreAlbum = $nombreAlbum;

        return $this;
    }

    /**
     * @return Collection<int, Albums>
     */
    public function getIdAlbum(): Collection
    {
        return $this->idAlbum;
    }

    public function addIdAlbum(Albums $idAlbum): self
    {
        if (!$this->idAlbum->contains($idAlbum)) {
            $this->idAlbum[] = $idAlbum;
            $idAlbum->setOrderDetail($this);
        }

        return $this;
    }

    public function removeIdAlbum(Albums $idAlbum): self
    {
        if ($this->idAlbum->removeElement($idAlbum)) {
            // set the owning side to null (unless already changed)
            if ($idAlbum->getOrderDetail() === $this) {
                $idAlbum->setOrderDetail(null);
            }
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStripesession(): ?string
    {
        return $this->stripesession;
    }

    public function setStripesession(?string $stripesession): self
    {
        $this->stripesession = $stripesession;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }




}
