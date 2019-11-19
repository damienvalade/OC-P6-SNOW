<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 * @UniqueEntity(fields={"username"}, message="Nom utilisateur déjà pris")
 * @UniqueEntity(fields={"email"}, message="Adresse email déjà utilisé")
 */
class Users implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="simple_array")
     */
    private $level_administration;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tricks", mappedBy="users", orphanRemoval=true)
     */
    private $tricks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commentaries", mappedBy="users", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reset_token;

    public const ROLE_USER = "ROLE_USER";
    public const ROLE_MODERATOR = "ROLE_MODERATOR";
    public const ROLE_EDITOR = "ROLE_EDITOR";
    public const ROLE_MANAGER = "ROLE_MANAGER";
    public const ROLE_ADMIN = "ROLE_ADMIN";

    public const LEVEL_ADMINISTRATION = [
        self::ROLE_USER,
        self::ROLE_MODERATOR,
        self::ROLE_EDITOR,
        self::ROLE_MANAGER,
        self::ROLE_ADMIN
    ];

    public function __construct()
    {
        $this->tricks = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getLevelAdministration(): ?array
    {
        return $this->level_administration;
    }

    public function setLevelAdministration(array $level_administration): self
    {
        $this->level_administration = $level_administration;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     * @return  (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return $this->level_administration;
    }

    public function setRoles(array $roles): self
    {
        $this->level_administration = $roles;
        return $this;
    }
    public function addRole(string $role)
    {
        $this->level_administration[] = $role;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Collection|Tricks[]
     */
    public function getTricks(): Collection
    {
        return $this->tricks;
    }

    public function addTrick(Tricks $trick): self
    {
        if (!$this->tricks->contains($trick)) {
            $this->tricks[] = $trick;
            $trick->setUsers($this);
        }

        return $this;
    }

    public function removeTrick(Tricks $trick): self
    {
        if ($this->tricks->contains($trick)) {
            $this->tricks->removeElement($trick);
            // set the owning side to null (unless already changed)
            if ($trick->getUsers() === $this) {
                $trick->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commentaries[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Commentaries $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUsers($this);
        }

        return $this;
    }

    public function removeComment(Commentaries $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUsers() === $this) {
                $comment->setUsers(null);
            }
        }

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }

}
