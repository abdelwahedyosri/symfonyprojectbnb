<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ad;
use App\Repository\AdRepository;
use App\Form\AdType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Image;
use  Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;







class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
    	/*$repo=$this->getDoctrine()->getRepository(Ad::class);*/
    	$ads=$repo->findAll();

        return $this->render('ad/index.html.twig', [
            'controller_name' => 'AdController',
            'ads'=>$ads
        ]);
    }

     /**
     * @Route("/ads/new", name="ads_create")
     * @IsGranted("ROLE_USER")
     */

    public function create(Request $request,ObjectManager $manager){
    	$ad=new Ad();


    	$form=$this->createForm(AdType::class,$ad);
    	$form->handlerequest($request);
    	if($form->isSubmitted() && $form->isValid()){
    		foreach ($ad->getImages() as $image) {

    				$image->setAd($ad);
    				$manager->persist($image);
    		}

    	
    		$manager->persist($ad);
    		$manager->flush();
    		$this->addFlash('success',

    			"l'annonce <strong>{$ad->getTitle()}</strong>a bien été enregistrée!"
    	);
    		return $this->redirecttoRoute('ads_show',['slug'=>$ad->getslug()]);
    	}
    	return $this->render('ad/new.html.twig',[
    		'form'=>$form->createView()
    	]);

    }

     /**
     * @Route("/ads/{slug}", name="ads_show")
     */
    public function show(Ad $ad,AdRepository $repo,$slug )
    {

    	$ad=$repo->findOneBySlug($slug);
    	return $this->render('ad/show.html.twig',[
    		'ad'=>$ad
    	]);
    }
 /**
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * @Security("is_granted('ROLE_USER') and user===ad.getAuthor()",message="Cette annonce ne vous appartient pas.Vous ne pouvez pas la modifier")
     */
    public function edit(Ad $ad ,Request $request,ObjectManager $manager)
    {
    	$form=$this->createForm(AdType::class,$ad);
    	$form->handlerequest($request);
    		if($form->isSubmitted() && $form->isValid()){
    		foreach ($ad->getImages() as $image) {

    				$image->setAd($ad);
    				$manager->persist($image);
    		}

    	
    		$manager->persist($ad);
    		$manager->flush();
    		$this->addFlash('success',

    			"les modifications de l'annonce <strong>{$ad->getTitle()}</strong>ont bien été enregistrée!"
    	);
    		return $this->redirecttoRoute('ads_show',['slug'=>$ad->getslug()]);
    	}

    	
    	return $this->render('ad/edit.html.twig',[
    		'form'=>$form->createView(),
    		'ad'=>$ad
    	]);

    }

     /**
     * @Route("/ads/{slug}/delete", name="ads_delete")
     * @Security("is_granted('ROLE_USER') and app.user==ad.getAuthor()",message="Vous n'avez pas le droit d'accéder à cette ressource")
     */
    public function delete(Ad $ad,ObjectManager $manager)
    {

        $manager->remove($ad);
        $manager->flush();

        $this->addFlash('danger','Votre annonce a bien été supprimée');
        return $this->redirectToRoute('ads_index');
    }

}
