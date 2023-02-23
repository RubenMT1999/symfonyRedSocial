<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 223, nullable: true)]
    private ?string $message = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $publication_date = null;

    #[ORM\ManyToOne]
    private ?User $id_user = null;

    #[ORM\OneToMany(mappedBy: 'id_post',targetEntity: Comments::class,cascade:['persist', 'remove'], )]
    private Collection $id_comments;


    #[ORM\OneToMany(mappedBy: 'id_post', targetEntity: Dislike::class, cascade: ['persist', 'remove'])]
    private Collection $id_dislike;

    #[ORM\OneToMany(mappedBy: 'id_post', targetEntity: Relio::class,cascade:['persist', 'remove'])]
    private Collection $id_relio;

    #[ORM\OneToMany(mappedBy: 'id_post', targetEntity: Megusta::class,cascade:['persist', 'remove'])]
    private Collection $id_megusta;



    public function __construct()
    {
        $this->id_comments = new ArrayCollection();
        $this->id_dislike = new ArrayCollection();
        $this->id_relio = new ArrayCollection();
        $this->id_megusta = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }


    public function getPublicationDate(): ?string
    {
        return $this->publication_date->format('Y-m-d');
    }

    public function setPublicationDate(\DateTimeInterface $publication_date): self
    {
        $this->publication_date = $publication_date;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getIdComments(): Collection
    {
        return $this->id_comments;
    }

    public function addIdComment(Comments $idComment): self
    {
        if (!$this->id_comments->contains($idComment)) {
            $this->id_comments->add($idComment);
            $idComment->setIdPost($this);
        }

        return $this;
    }

    public function removeIdComment(Comments $idComment): self
    {
        if ($this->id_comments->removeElement($idComment)) {
            // set the owning side to null (unless already changed)
            if ($idComment->getIdPost() === $this) {
                $idComment->setIdPost(null);
            }
        }

        return $this;
    }



    /**
     * @return Collection<int, Dislike>
     */
    public function getIdDislike(): Collection
    {
        return $this->id_dislike;
    }

    public function addIdDislike(Dislike $idDislike): self
    {
        if (!$this->id_dislike->contains($idDislike)) {
            $this->id_dislike->add($idDislike);
            $idDislike->setIdPost($this);
        }

        return $this;
    }

    public function removeIdDislike(Dislike $idDislike): self
    {
        if ($this->id_dislike->removeElement($idDislike)) {
            // set the owning side to null (unless already changed)
            if ($idDislike->getIdPost() === $this) {
                $idDislike->setIdPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Relio>
     */
    public function getIdRelio(): Collection
    {
        return $this->id_relio;
    }

    public function addIdRelio(Relio $idRelio): self
    {
        if (!$this->id_relio->contains($idRelio)) {
            $this->id_relio->add($idRelio);
            $idRelio->setIdPost($this);
        }

        return $this;
    }

    public function removeIdRelio(Relio $idRelio): self
    {
        if ($this->id_relio->removeElement($idRelio)) {
            // set the owning side to null (unless already changed)
            if ($idRelio->getIdPost() === $this) {
                $idRelio->setIdPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Megusta>
     */
    public function getIdMegusta(): Collection
    {
        return $this->id_megusta;
    }

    public function addIdMegustum(Megusta $idMegustum): self
    {
        if (!$this->id_megusta->contains($idMegustum)) {
            $this->id_megusta->add($idMegustum);
            $idMegustum->setIdPost($this);
        }

        return $this;
    }

    public function removeIdMegustum(Megusta $idMegustum): self
    {
        if ($this->id_megusta->removeElement($idMegustum)) {
            // set the owning side to null (unless already changed)
            if ($idMegustum->getIdPost() === $this) {
                $idMegustum->setIdPost(null);
            }
        }

        return $this;
    }


}
