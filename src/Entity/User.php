<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository", repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $user_name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email_user;

    /**
     * @var string the hashed password
     * @ORM\Column(type="string", length=255)
     */
    private $password_user;

    /**
     * @ORM\Column(type="integer")
     */
    private $phone_user;

    /**
     * @ORM\OneToMany(targetEntity=Immobilier::class, mappedBy="user")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity=Immobilier::class, mappedBy="favoris")
     */
    private $favoris;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->favoris = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserName(): ?string
    {
        return $this->user_name;
    }

    public function setUserName(string $user_name): self
    {
        $this->user_name = $user_name;

        return $this;
    }

    public function getEmailUser(): ?string
    {
        return $this->email_user;
    }

    public function setEmailUser(string $email_user): self
    {
        $this->email_user = $email_user;

        return $this;
    }

    public function getPasswordUser(): ?string
    {
        return $this->password_user;
    }

    public function setPasswordUser(string $password_user): self
    {
        $this->password_user = $password_user;

        return $this;
    }

    public function getPhoneUser(): ?int
    {
        return $this->phone_user;
    }

    public function setPhoneUser(int $phone_user): self
    {
        $this->phone_user = $phone_user;

        return $this;
    }

    /**
     * @return Collection|Immobilier[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Immobilier $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setUser($this);
        }

        return $this;
    }

    public function removeUser(Immobilier $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getUser() === $this) {
                $user->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Immobilier[]
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Immobilier $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris[] = $favori;
            $favori->addFavori($this);
        }

        return $this;
    }

    public function removeFavori(Immobilier $favori): self
    {
        if ($this->favoris->removeElement($favori)) {
            $favori->removeFavori($this);
        }

        return $this;
    }


}