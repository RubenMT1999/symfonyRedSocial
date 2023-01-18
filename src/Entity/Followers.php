<?php

namespace App\Entity;

use App\Repository\FollowersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FollowersRepository::class)]
class Followers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?User $id_emisor = null;

    #[ORM\ManyToOne]
    private ?User $id_receptor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdEmisor(): ?User
    {
        return $this->id_emisor;
    }

    public function setIdEmisor(?User $id_emisor): self
    {
        $this->id_emisor = $id_emisor;

        return $this;
    }

    public function getIdReceptor(): ?User
    {
        return $this->id_receptor;
    }

    public function setIdReceptor(?User $id_receptor): self
    {
        $this->id_receptor = $id_receptor;

        return $this;
    }
}
