<?php

namespace App\Entity;

use App\Repository\PurchasesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PurchasesRepository::class)
 */
class Purchases
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="purchases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur_id;

    /**
     * @ORM\OneToOne(targetEntity=Albums::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $album_id;

    /**
     * @ORM\Column(type="date")
     */
    private $date_purchases;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateurId(): ?User
    {
        return $this->utilisateur_id;
    }

    public function setUtilisateurId(?User $utilisateur_id): self
    {
        $this->utilisateur_id = $utilisateur_id;

        return $this;
    }

    public function getAlbumId(): ?Albums
    {
        return $this->album_id;
    }

    public function setAlbumId(Albums $album_id): self
    {
        $this->album_id = $album_id;

        return $this;
    }

    public function getDatePurchases(): ?\DateTimeInterface
    {
        return $this->date_purchases;
    }

    public function setDatePurchases(\DateTimeInterface $date_purchases): self
    {
        $this->date_purchases = $date_purchases;

        return $this;
    }
}
