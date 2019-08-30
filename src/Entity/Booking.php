<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 *@ORM\HaslifecycleCallBacks()
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ad", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="la date d'arrivée doit etre au bon format")
     */
    private $startdate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="la date de départ doit etre au bon format")
     */
    private $enddate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $comment;

    

    /**
     * @ORM\PrePersist()
     */
    public function prePersist(){

        if(empty($this->createdAt)){
            $this->createdAt=new \DateTime();
        }
        if(empty($this->amount)){
                $this->amount=$this->ad->getprice()*$this->getDuration();
        }
    }


    public function  getDuration(){
        $diff=$this->enddate->diff($this->startdate);
        return $diff->days;

    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartdate(): ?\DateTimeInterface
    {
        return $this->startdate;
    }

    public function setStartdate(\DateTimeInterface $startdate): self
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getEnddate(): ?\DateTimeInterface
    {
        return $this->enddate;
    }

    public function setEnddate(\DateTimeInterface $enddate): self
    {
        $this->enddate = $enddate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    
public function isBookableDate(){
    $notAvailableDates=$this->ad->getNotAvailableDays();
    $bookingdays=$this->getDays();
    $days=array_map(function($day){
        return $day->format('Y-m-d');
    },$bookingdays);
}
public function getDays(){
   
   
        $result=range(
            $booking->getStartDate()->getTimestamp(),
            $booking->getEndDate()->getTimestamp(),
            24*60*60*1000
        );
        $days=array_map(function($dayTimestamp){

            return new \DateTime(date('Y-m-d',$dayTimestamp));
        });
        return $days;
}
}
