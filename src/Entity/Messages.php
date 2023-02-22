<?php

namespace App\Entity;

use App\Repository\MessagesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessagesRepository::class)]
class Messages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $texto = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $creation_date = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?User $usuario_emisor = null;

    #[ORM\ManyToOne]
    private ?User $usuario_receptor = null;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function setTexto(?string $texto): self
    {
        $this->texto = $texto;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(?\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getUsuarioEmisor(): ?User
    {
        return $this->usuario_emisor;
    }

    public function setUsuarioEmisor(?User $usuario_emisor): self
    {
        $this->usuario_emisor = $usuario_emisor;

        return $this;
    }

    public function getUsuarioReceptor(): ?User
    {
        return $this->usuario_receptor;
    }

    public function setUsuarioReceptor(?User $usuario_receptor): self
    {
        $this->usuario_receptor = $usuario_receptor;

        return $this;
    }

}
