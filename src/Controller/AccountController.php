<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use  Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;





class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     */
    public function login(AuthenticationUtils $utils)
    {
    	$error=$utils->getLastAuthenticationError();
    	$username=$utils->getLastUsername();
        return $this->render('account/login.html.twig',[
        	'haserror'=>$error!=null,
        	'username'=>$username
        ]);
    }
    /**
     * @Route("/logout", name="account_logout")
     */
    public function logout()
    {
        return $this->render('account/login.html.twig');
    }

      /**
     * @Route("/register", name="account_register")
     */
    public function register(Request $request,ObjectManager $manager,UserPasswordEncoderInterface $encoder)
    {
    	$user=new User();
    	$form=$this->createForm(RegistrationType::class,$user);
    	$form->handlerequest($request);
    	if ($form->isSubmitted() && $form->isValid()){

    		$hash=$encoder->encodePassword($user,$user->getHash());
    		$user->setHash($hash);
    		$manager->persist($user);
    		$manager->flush();
    		$this->addFlash('success','Votre compte a bien été crée,vous pouvez maintenant vous connecter');
    		return $this->redirectToRoute('account_login');
    	}
        return $this->render('account/registration.html.twig',[
        	'form'=>$form->createView()
        ]);
    }

/**
     * @Route("/account_profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     */
    public function profile(Request $request,ObjectManager $manager){
    	$user=$this->getUser();

    	$form=$this->createForm(AccountType::class,$user);
    	$form->handlerequest($request);
    	if($form->isSubmitted() && $form->isValid()){

    		$manager->persist($user);
    		$manager->flush();
    		$this->addFlash('success','les données du profil ont été enregistrés');
    	}


    	return $this->render('account/profile.html.twig',[
    		'form'=>$form->createView()
    	]);
    }
/**
     * @Route("/account_updatepassword", name="account_password")
     */

    public function updatepassword(Request $request,UserPasswordEncoderInterface $encoder,ObjectManager $manager){

    	$passwordupdate=new PasswordUpdate();
    	$user=$this->getUser();
    	$form=$this->createForm(PasswordUpdateType::class,$passwordupdate);
    	$form->handlerequest($request);
    	if($form->isSubmitted() && $form->isValid()){
    		if(!password_verify($passwordupdate->getOldpassword(),$user->getHash())){
    			$form->get('Oldpassword')->addError(new FormError("le mot de passe que vous avez tapé n'est pas votre mot de passe actuel"));


    	}else{

    		$newpassword=$passwordupdate->getNewpassword();
    		$hash=$encoder->encodePassword($user,$newpassword);
    		$user->setHash($hash);
    		$manager->persist($user);
    		$manager->flush();
    		$this->addFlash('success','votre mot de passe a bien été modifié');
    		return $this->redirectToRoute('home_page');
    	}
    }

    	return $this->render('account/password.html.twig',[

    		"form"=>$form->createView()



    	]);

    }
/**
     * @Route("/account", name="account_index")
     *  @IsGranted("ROLE_USER")
     */
    public function myAccount(){
    	

    		$user=$this->getUser();
    		
    	


    	return $this->render('user/index.html.twig',[
    		'user'=>$user
    	]);

    }
}
