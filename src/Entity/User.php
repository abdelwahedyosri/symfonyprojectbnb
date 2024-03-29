<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *  @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields={"email"},message="un autre utlisateur s'est inscrit déja avec cet email,merci de le modiffier")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner votre nom de famille")
     */
    private $Firstname;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank(message="Vous devez renseigner votre prénom")
     */
    private $Lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Veuillez renseigner un email valide")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Assert\Url(message="Veuillez renseigner une url valide pour votre avatar")
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=20,minMessage="Votre description doit faire au moins 20 caractères")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ad", mappedBy="author")
     */
    private $ads;
     /**
     * @Assert\EqualTo(propertyPath="hash",message="vous n'avez pas confirmer correctment votre mot de passe")
     */

    public $passwordConfirm;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", mappedBy="users")
     */
    private $userRoles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="booker")
     */
    private $bookings;

    public function __construct()
    {
        $this->ads = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

     /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */

    public function initializeslug(){

        if(empty($this->slug)){

            $slugify= new Slugify();
            $this->slug=$slugify->slugify($this->Firstname.' '.$this->Lastname);

        }

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->Firstname;
    }

    public function setFirstname(string $Firstname): self
    {
        $this->Firstname = $Firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->Lastname;
    }

    public function setLastname(string $Lastname): self
    {
        $this->Lastname = $Lastname;

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

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Ad[]
     */
    public function getAds(): Collection
    {
        return $this->ads;
    }

    public function addAd(Ad $ad): self
    {
        if (!$this->ads->contains($ad)) {
            $this->ads[] = $ad;
            $ad->setAuthor($this);
        }

        return $this;
    }

    public function removeAd(Ad $ad): self
    {
        if ($this->ads->contains($ad)) {
            $this->ads->removeElement($ad);
            // set the owning side to null (unless already changed)
            if ($ad->getAuthor() === $this) {
                $ad->setAuthor(null);
            }
        }

        return $this;
    }
    public function getRoles()
         {
                $roles=$this->userRoles->map(function($role){

                    return $role->getTitle();
                })->toArray();

                $roles[]='ROLE_USER';


             return $roles;
         }

    public function getPassword(){
        return $this->hash;
    }
    public function getSalt(){


    } 
    public function getUsername(){
        return $this->email;
    }  
     public function eraseCredentials(){


     }

     /**
      * @return Collection|Role[]
      */
     public function getUserRoles(): Collection
     {
         return $this->userRoles;
     }

     public function addUserRole(Role $userRole): self
     {
         if (!$this->userRoles->contains($userRole)) {
             $this->userRoles[] = $userRole;
             $userRole->addUser($this);
         }

         return $this;
     }

     public function removeUserRole(Role $userRole): self
     {
         if ($this->userRoles->contains($userRole)) {
             $this->userRoles->removeElement($userRole);
             $userRole->removeUser($this);
         }

         return $this;
     }

     /**
      * @return Collection|Booking[]
      */
     public function getBookings(): Collection
     {
         return $this->bookings;
     }

     public function addBooking(Booking $booking): self
     {
         if (!$this->bookings->contains($booking)) {
             $this->bookings[] = $booking;
             $booking->setBooker($this);
         }

         return $this;
     }

     public function removeBooking(Booking $booking): self
     {
         if ($this->bookings->contains($booking)) {
             $this->bookings->removeElement($booking);
             // set the owning side to null (unless already changed)
             if ($booking->getBooker() === $this) {
                 $booking->setBooker(null);
             }
         }

         return $this;
     }


}
