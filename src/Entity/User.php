<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use OpenApi\Attributes as OA;


#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserProfile $userProfile = null;

    #[ORM\OneToMany(mappedBy: 'id_user', targetEntity: Comments::class)]
    private Collection $id_comments;


    #[ORM\OneToMany(mappedBy: 'id_user', targetEntity: Dislike::class)]
    private Collection $id_dislike;

    #[ORM\OneToMany(mappedBy: 'id_user', targetEntity: Relio::class)]
    private Collection $id_relio;

    #[ORM\OneToMany(mappedBy: 'usuario_emisor', targetEntity: Messages::class)]
    private Collection $messages;





    public function __construct()
    {
        $this->id_comments = new ArrayCollection();
        $this->id_dislike = new ArrayCollection();
        $this->id_relio = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUserProfile(): ?UserProfile
    {
        return $this->userProfile;
    }

    public function setUserProfile(UserProfile $userProfile): self
    {
        // set the owning side of the relation if necessary
         {
            $userProfile->setUser($this);
        }

        $this->userProfile = $userProfile;

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
            $idComment->setIdUser($this);
        }

        return $this;
    }

    public function removeIdComment(Comments $idComment): self
    {
        if ($this->id_comments->removeElement($idComment)) {
            // set the owning side to null (unless already changed)
            if ($idComment->getIdUser() === $this) {
                $idComment->setIdUser(null);
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
            $idDislike->setIdUser($this);
        }

        return $this;
    }

    public function removeIdDislike(Dislike $idDislike): self
    {
        if ($this->id_dislike->removeElement($idDislike)) {
            // set the owning side to null (unless already changed)
            if ($idDislike->getIdUser() === $this) {
                $idDislike->setIdUser(null);
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
            $idRelio->setIdUser($this);
        }

        return $this;
    }

    public function removeIdRelio(Relio $idRelio): self
    {
        if ($this->id_relio->removeElement($idRelio)) {
            // set the owning side to null (unless already changed)
            if ($idRelio->getIdUser() === $this) {
                $idRelio->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Messages>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Messages $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setUsuarioEmisor($this);
        }

        return $this;
    }

    public function removeMessage(Messages $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUsuarioEmisor() === $this) {
                $message->setUsuarioEmisor(null);
            }
        }

        return $this;
    }






}
