<?php

namespace App\Entity;

use App\Repository\UnsubscribeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UnsubscribeRepository::class)
 */
class Unsubscribe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur_id;

    /**
     * @ORM\Column(type="date")
     */
    private $date_unsubscribe;

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

    public function getDateUnsubscribe(): ?\DateTimeInterface
    {
        return $this->date_unsubscribe;
    }

    public function setDateUnsubscribe(\DateTimeInterface $date_unsubscribe): self
    {
        $this->date_unsubscribe = $date_unsubscribe;

        return $this;
    }
}
