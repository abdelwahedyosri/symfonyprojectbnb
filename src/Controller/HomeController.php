<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function home()
    {
    	$tab=['yosri'=>31,'rihen'=>12,'raed'=>55];
    	$age=19;
       return $this->render(


       	'home/home.html.twig',[

       		'title'=>'bonjour à tous',
       		'age'=> $age,
       		'tab'=>$tab
       ]);
    }

/**
       * @Route("/hello/{prenom}/{age}", name="hello")
     */
    public function hello($prenom="yosri",$age="30 ans")
    {
       return new Response('hello'.'  '.$prenom."<br> vous avez"."  ".$age);


       	
    }
}
