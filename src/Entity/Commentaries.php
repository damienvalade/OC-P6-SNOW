<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentariesRepository")
 */
class Commentaries
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
    private $commentarie;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_valide = false;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tricks", inversedBy="commentaries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tricks;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentarie(): ?string
    {
        return $this->commentarie;
    }

    public function setCommentarie(string $commentarie): self
    {
        $this->commentarie = $commentarie;

        return $this;
    }

    public function getIsValide(): ?bool
    {
        return $this->is_valide;
    }

    public function setIsValide(bool $is_valide): self
    {
        $this->is_valide = $is_valide;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTimeInterface $create_at): self
    {
        $this->create_at = $create_at;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function getTricks(): ?Tricks
    {
        return $this->tricks;
    }

    public function setTricks(?Tricks $tricks): self
    {
        $this->tricks = $tricks;

        return $this;
    }
}
