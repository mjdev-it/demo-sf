<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @Assert(UniqueEntity
 * fields= "email",
 * message= "l'Email que vous indiqué est déjà utilisé!" 
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert(Email)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\length(min="8", minMessage = "Votre mot de passe doit faire au moins 8 caracteres")
     * @Assert\equalTo(propertyPath="confirm_password", message="Vous n'avvez pas taper 2 fois le même mot de passe !")
     */
    private $password;

    /*
    ## mj le 17/11/20 pass d'annotations @ORM
    ## pr indiquer que ce champ ne va pas aller en bdd
    ## on aurai pu le mettre en privé, en public l'avantage
    ## c'est qu'on n'est pas obligé de rajouter les assesseurs (mode demo) */
     /**
     * @Assert\equalTo(propertyPath="password")
     */
    public $confirm_password;

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
}
