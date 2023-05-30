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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fullnameuser;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stripesession;


    /**
     * @ORM\Column(type="integer")
     */
    private $album;


    /**
     * @ORM\Column(type="float")
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function setAlbum(?int $album): self
    {
        $this->album = $album;
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

    public function setFullnameuser(string $fullnameuser): self
    {
        $this->fullnameuser = $fullnameuser;

        return $this;
    }

    public function getFullnameuser(): ?string
    {
        return $this->fullnameuser;
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

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAlbum(): ?int
    {
        return $this->album;
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
}
