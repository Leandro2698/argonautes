<?php

namespace App\Controller;

use App\Entity\Argonaute;
use App\Form\ArgonauteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController

{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(Request $request): Response
    {
        $argonautes = $this->em->getRepository(Argonaute::class)->findAll();
        
        $argonaute = new Argonaute;
        $addArgonauteForm = $this->createForm(ArgonauteType::class, $argonaute);

        $addArgonauteForm->handleRequest($request);

        if ($addArgonauteForm->isSubmitted() && $addArgonauteForm->isValid()) {
            $argonaute = $addArgonauteForm->getData();
            $this->em->persist($argonaute);
            $this->em->flush();
            return $this->redirectToRoute("home");
        }
        return $this->render('home/index.html.twig', [
            'add_argonaute_form' => $addArgonauteForm->createView(),
            'argonautes' => $argonautes
        ]);

        
    }
    

}
