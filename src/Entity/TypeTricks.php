<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeTricksRepository")
 */
class TypeTricks
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tricks", mappedBy="type_tricks", orphanRemoval=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name_tricks;

    public function __construct()
    {
        $this->name = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Tricks[]
     */
    public function getName(): Collection
    {
        return $this->name;
    }

    public function addName(Tricks $name): self
    {
        if (!$this->name->contains($name)) {
            $this->name[] = $name;
            $name->setTypeTricks($this);
        }

        return $this;
    }

    public function removeName(Tricks $name): self
    {
        if ($this->name->contains($name)) {
            $this->name->removeElement($name);
            // set the owning side to null (unless already changed)
            if ($name->getTypeTricks() === $this) {
                $name->setTypeTricks(null);
            }
        }

        return $this;
    }

    public function getNameTricks(): ?string
    {
        return $this->name_tricks;
    }

    public function setNameTricks(string $name_triks): self
    {
        $this->name_tricks = $name_triks;

        return $this;
    }


}
