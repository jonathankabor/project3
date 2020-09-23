<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use ContainerRc3kAf4\getForm_ChoiceListFactory_CachedService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods="GET")
     * @param PinRepository $pinRepository
     * @return Response
     */
    public function index(PinRepository $pinRepository): Response
    {
        $pins = $pinRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('pins/index.html.twig', compact('pins'));
    }

    /**
     * @Route("/pins/create", name="app_pins_create", methods="GET|POST")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $pin = new Pin;
        $form =$this->createForm(PinType::class, $pin);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            //$pin->setUser($this->getUser());
            $em->persist($pin);
            $em->flush();

            $this->addFlash('success', 'Pin successfully created !');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/create.html.twig',[
            'form'=> $form->createView()
        ]);

    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show", methods="GET")
     * @param Pin $pin
     * @return Response
     */
    public function show(Pin $pin): Response
    {
       return $this->render('pins/show.html.twig', compact('pin'));

    }

    /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="app_pins_edit", methods="GET|PUT")
     * @param Pin $pin
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function edit(Pin $pin,EntityManagerInterface $em, Request $request): Response
    {

        $form =$this->createForm(PinType::class, $pin, [
            'method'=> 'PUT'
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();

            $this->addFlash('success', 'Pin successfully Updated !');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/edit.html.twig', [
            'pin'=> $pin,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_delete", methods="DELETE")
     * @param Request $request
     * @param Pin $pin
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(Request $request,Pin $pin,EntityManagerInterface $em): Response
    {
       if ($this->isCsrfTokenValid('pin_deletion_' . $pin->getId(),
            $request->request->get('csrf_token'))){

           $em->remove($pin);
           $em->flush();

           $this->addFlash('success', 'Pin successfully deleted !');

       }

        return $this->redirectToRoute('app_home');

    }
}
