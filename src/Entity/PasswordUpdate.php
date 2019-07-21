<?php

namespace App\Entity;


use Symfony\Component\Validator\Constraints as Assert;


class PasswordUpdate
{
   
   

    
    private $Oldpassword;

    /**
     * @Assert\Length(min=8, minMessage="Votre mot de passe doit faire au moins 8 caractères!")
     * 
     */
    private $newpassword;

    /**
     * @Assert\EqualTo(propertyPath="newpassword", message="vous n'avez pas correctement confirmé vote mot de passe")
     * 
     */
    private $confirmpassword;

   

    public function getOldpassword(): ?string
    {
        return $this->Oldpassword;
    }

    public function setOldpassword(string $Oldpassword): self
    {
        $this->Oldpassword = $Oldpassword;

        return $this;
    }

    public function getNewpassword(): ?string
    {
        return $this->newpassword;
    }

    public function setNewpassword(string $newpassword): self
    {
        $this->newpassword = $newpassword;

        return $this;
    }

    public function getConfirmpassword(): ?string
    {
        return $this->confirmpassword;
    }

    public function setConfirmpassword(string $confirmpassword): self
    {
        $this->confirmpassword = $confirmpassword;

        return $this;
    }
}
