<?php

namespace App\Controller;

use App\Entity\Agency;
use App\Form\Agency1Type;
use App\Repository\AgencyRepository;
use App\Repository\SocietyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/agency")
 */
class AgencyController extends AbstractController
{
    /**
     * @Route("/", name="agency_index", methods={"GET"})
     */
    public function index(SocietyRepository $societyRepository, AgencyRepository $agencyRepository): Response
    {
        return $this->render('agency/index.html.twig', [
            'agencies' => $agencyRepository->findAll(),
            'society'=> $societyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="agency_new", methods={"GET","POST"})
     */
    public function new(SocietyRepository $societyRepository,Request $request): Response
    {
        $agency = new Agency();
        $form = $this->createForm(Agency1Type::class, $agency);
        $form->handleRequest($request);
        $society=$societyRepository->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($society as $s) {
               $caisseg=$s->getCaisse();
               $agencycaisse=$agency->getCaisse();
               $caisseg+=$agencycaisse;
               $s->setCaisse($caisseg);
            }
            $entityManager->persist($agency);
            $entityManager->flush();
            $this->addFlash("success", "Agence crée avec succès!");
            return $this->redirectToRoute('agency_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('agency/new.html.twig', [
            'agency' => $agency,
            'form' => $form,
            'society'=>$societyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="agency_show", methods={"GET"})
     */
    public function show(SocietyRepository $societyRepository, Agency $agency): Response
    {
        return $this->render('agency/show.html.twig', [
            'agency' => $agency,
            'society'=>$societyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="agency_edit", methods={"GET","POST"})
     */
    public function edit(SocietyRepository $societyRepository, Request $request, Agency $agency): Response
    {
        $agencyy=$agency;
        $agencycaisse=$agencyy->getCaisse();
        $form = $this->createForm(Agency1Type::class, $agency);
        $form->handleRequest($request);
        $society=$societyRepository->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            // if ($form['caisse']->getData()!='') {
            //     $upcaisse=$form['caisse']->getData();
                
            //     foreach ($society as $s) {
            //         $caisseg=$s->getCaisse();
            //         $caisseg+=$upcaisse;
            //         $agencycaisse+=$upcaisse;
            //         $s->setCaisse($caisseg);
            //         $agency->setCaisse($agencycaisse);
            //     }
                
            // }
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", "Agence modifiée avec succès!");
            return $this->redirectToRoute('agency_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('agency/edit.html.twig', [
            'agency' => $agency,
            'form' => $form,
            'society'=>$societyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="agency_delete", methods={"POST"})
     */
    public function delete(SocietyRepository $societyRepository, Request $request, Agency $agency): Response
    {
        if ($this->isCsrfTokenValid('delete'.$agency->getId(), $request->request->get('_token'))) {
            $society=$societyRepository->findAll();
            // foreach ($society as $s) {
            //     $caisseg=$s->getCaisse();
            //     $agencycaisse=$agency->getCaisse();
            //     $caisseg+=$agencycaisse;
            //     $s->setCaisse($caisseg);
            // }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($agency);
            $entityManager->flush();
        }
        $this->addFlash("success", "Agence supprimée avec succès!");
        return $this->redirectToRoute('agency_index', [], Response::HTTP_SEE_OTHER);
    }
}
