<?php

namespace App\Entity;

use App\Repository\AlbumsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


/**
 * @ORM\Entity(repositoryClass=AlbumsRepository::class)
 */
class Albums
{
    private $session;
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
     * @ORM\OneToMany(targetEntity=Instrumentals::class, mappedBy="album_id", orphanRemoval=true)
     */
    private $instrumentals;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=OrderDetail::class, inversedBy="idAlbum",cascade={"persist"})
     */
    private $orderDetail;

    /**
     * @ORM\OneToOne(targetEntity=Contrat::class, inversedBy="albums", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $contrat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    public function __construct()
    {
       /* $this->session = $session;*/
        $this->instrumentals = new ArrayCollection();
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
        if($image == null) {
            $image = 'default.jpg';
        }
        else {
            $this->image = $image;
        }

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }
    public function __toString(): string
    {
        return $this->getTitle();
    }
   /* public function isAddToCart() {
        $bool = false;
        $cart = $this->session->get('cart', []);
        $inverse = array_flip($cart);
        if(array_key_exists($this->getId(),$inverse)) {
            $bool = true;
        }
        return $bool;
    }*/

    public function getOrderDetail(): ?OrderDetail
    {
        return $this->orderDetail;
    }

    public function setOrderDetail(?OrderDetail $orderDetail): self
    {
        $this->orderDetail = $orderDetail;

        return $this;
    }

    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }

    public function setContrat(Contrat $contrat): self
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
        if($status == null) {
            $status = false;
        }
        $this->status = $status;

        return $this;
    }

}
