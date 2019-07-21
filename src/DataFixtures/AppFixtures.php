<?php

namespace App\DataFixtures;
use Faker\Factory;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Ad;
use App\Entity\Image;
use App\Entity\User;
use App\Entity\Role;
use App\Entity\Booking;

use  Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class AppFixtures extends Fixture
{

	private $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder){

		$this->encoder=$encoder;
	}
    public function load(ObjectManager $manager)
    {

    	$faker=Factory::create('FR-fr');
      $adminRole=new Role();
      $adminRole->setTitle('ROLE_ADMIN');
     
      $manager->persist($adminRole);

      $adminUser=new User();
      $adminUser->setFirstname('Abdelwahed')
                 ->setLastname('yosri')
                 ->setEmail('Abdelwahed_yosri@yahoo.com')
                 ->setIntroduction($faker->sentence())
                 ->setDescription('<p>'.join('</p><p>',$faker->paragraphs(5)).'</p>')
                 ->setHash($this->encoder->encodePassword($adminUser,'password'))
                 ->setPicture('https://randomuser.me/api/portraits/men/20.jpg')
                 ->addUserRole($adminRole);
       $manager->persist($adminUser);          


    	$users=[];
    	$genres=['male','female'];

    	
    	for($i=1;$i<=10;$i++){

    		$user=new User();
    		$hash=$this->encoder->encodePassword($user,'password');
    		$genre=$faker->randomElement($genres);
    		$picture='https://randomuser.me/api/portraits';
    		$pictureid=$faker->numberBetween(1,99).'.jpg';
    		if($genre=='male'){

    			$picture=$picture.'/men/'.$pictureid;
    		}else{
    			$picture=$picture.'/women/'.$pictureid;

    		}
    		$user->setFirstname($faker->firstname($genre))->setLastname($faker->lastname($genre))->setEmail($faker->email)->setIntroduction($faker->sentence())->setDescription('<p>'.join('</p><p>',$faker->paragraphs(5)).'</p>')->setHash($hash)->setPicture($picture);
    		$manager->persist($user);
    		$users[]=$user;
    	}

       for ($i=1; $i<=30 ; $i++){
       $ad=new Ad();
       $title=$faker->sentence();
       $coverImage=$faker->imageUrl(1000,350);
       $introduction=$faker->paragraph(2);
       $content='<p>'.join('</p><p>',$faker->paragraphs(5)).'</p>';
       $user=$users[mt_rand(0,9)];
      
       $ad->setTitle( $title)->setCoverImage($coverImage)->setIntroduction($introduction)->setContent($content)->setPrice(mt_rand(40,200))->setRooms(mt_rand(1,6))->setAuthor($user);
       for ($j=1;$j<=mt_rand(2,5);$j++){
       	$image=new Image();
       	$image->setUrl($faker->imageUrl())->setCaption($faker->sentence())->setAd($ad);

       	 $manager->persist($image);

         for ($j=1;$j<=mt_rand(0,10);$j++){

          $booking=new Booking();
          $startdate=$faker->DateTimeBetween('-3 months');
          $createdAt=$faker->DateTimeBetween('-6 months');
          $duration=mt_rand(3,10);
          $amount=$ad->getprice()*$duration;
          $enddate=(clone $startdate)->modify("+$duration days");
          $comment=$faker->paragraph(2);
          
          $booking->setstartdate($startdate)
                   ->setenddate($enddate)
                   ->setcreatedAt($createdAt)
                   ->setAd($ad)
                   ->setbooker($users[mt_rand(0,count($users)-1)])
                   ->setamount($amount)
                   ->setcomment($comment);



          $manager->persist($booking);          


         }
       }
       $manager->persist($ad);
}
        $manager->flush();
    }
}
