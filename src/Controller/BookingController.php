<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\BookingType;
use  Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

class BookingController extends AbstractController
{
    /**
     * @Route("/ads/{slug}/book", name="booking_create")
     *@IsGranted("ROLE_USER")
     */
    public function book(Ad $ad,Request $request,ObjectManager $manager)
    {
    	$booking=new Booking();
    	$form=$this->createForm(BookingType::class,$booking);
    	$form->handlerequest($request);
    	if($form->isSubmitted() and $form->isValid()){
    		$user=$this->getUser();
    		$booking->setad($ad)
    			->setbooker($user);
    		$manager->persist($booking);
    		$manager->flush();
    		return $this->redirectToRoute('booking_show',['id'=>$booking->getId(),

    			'with_alert'=>true

    		]);
    	}


        return $this->render('booking/book.html.twig', [
        	'ad'=>$ad, 
        	'form'=>$form->createView()
            
        ]);
    }

    /**
     * @Route("/booking/{id}", name="booking_show")
     *
     */

    public function book_show(Booking $booking){

    	return $this->render('booking/show.html.twig',[
    		'booking'=>$booking
    	]);

    }
}
