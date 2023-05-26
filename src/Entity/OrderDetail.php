<?php

namespace App\Entity;

use App\Repository\OrderDetailRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderDetailRepository::class)
 */
class OrderDetail
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

    public function __construct()
    {
        $this->idAlbum = new ArrayCollection();
    }

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
}
