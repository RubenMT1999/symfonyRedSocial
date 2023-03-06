<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentsRepository::class)]
class Comments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 500)]
    private ?string $text = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_comments = null;

    #[ORM\ManyToOne(inversedBy: 'id_comments')]
    private ?Post $id_post = null;

    #[ORM\ManyToOne(inversedBy: 'id_comments')]
    private ?User $id_user = null;

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateComments(): ?string
    {
        return $this->date_comments->format('Y-m-d');
    }

    /**
     * @param \DateTimeInterface|null $date_comments
     */
    public function setDateComments(?\DateTimeInterface $date_comments): self
    {
        $this->date_comments = $date_comments;
        return $this;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getIdPost(): ?Post
    {
        return $this->id_post;
    }

    public function setIdPost(?Post $id_post): self
    {
        $this->id_post = $id_post;

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
}
