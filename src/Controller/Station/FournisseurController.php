<?php

namespace App\Controller\Station;

use App\Entity\Fournisseur;
use App\Form\FournisseurType;
use App\Repository\FournisseurRepository;
use App\Repository\SocietyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/fournisseur")
 */
class FournisseurController extends AbstractController
{
    private SocietyRepository $societyRepository;

    public function __construct(SocietyRepository $societyRepository)
    {
        $this->societyRepository = $societyRepository;

    }

    /**
     * @Route("/", name="app_fournisseur_index", methods={"GET"})
     */
    public function index(FournisseurRepository $fournisseurRepository): Response
    {
        $caisseStation = $this->societyRepository->findBy([],[],[1])[0]->getCaisseStation();

        return $this->render('station-dashboard/fournisseur/index.html.twig', [
            'fournisseurs' => $fournisseurRepository->findAll(),
            'caisseStation' =>number_format($caisseStation, 0, ',','.')??0,
        ]);
    }

    /**
     * @Route("/new", name="app_fournisseur_new", methods={"GET", "POST"})
     */
    public function new(Request $request, FournisseurRepository $fournisseurRepository): Response
    {
        $fournisseur = new Fournisseur();
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fournisseurRepository->add($fournisseur);
            return $this->redirectToRoute('app_fournisseur_index', [], Response::HTTP_SEE_OTHER);
        }

        $caisseStation = $this->societyRepository->findBy([],[],[1])[0]->getCaisseStation();

        return $this->renderForm('station-dashboard/fournisseur/new.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form,
            'caisseStation' =>number_format($caisseStation, 0, ',','.')??0,
        ]);
    }

    /**
     * @Route("/{id}", name="app_fournisseur_show", methods={"GET"})
     */
    public function show(Fournisseur $fournisseur): Response
    {
        $caisseStation = $this->societyRepository->findBy([],[],[1])[0]->getCaisseStation();
        return $this->render('station-dashboard/fournisseur/show.html.twig', [
            'fournisseur' => $fournisseur,
            'caisseStation' =>number_format($caisseStation, 0, ',','.')??0,

        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_fournisseur_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Fournisseur $fournisseur, FournisseurRepository $fournisseurRepository): Response
    {
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fournisseurRepository->add($fournisseur);
            return $this->redirectToRoute('app_fournisseur_index', [], Response::HTTP_SEE_OTHER);
        }

        $caisseStation = $this->societyRepository->findBy([],[],[1])[0]->getCaisseStation();
        return $this->renderForm('station-dashboard/fournisseur/edit.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form,
            'caisseStation' =>number_format($caisseStation, 0, ',','.')??0,
        ]);
    }

    /**
     * @Route("/{id}", name="app_fournisseur_delete", methods={"POST"})
     */
    public function delete(Request $request, Fournisseur $fournisseur, FournisseurRepository $fournisseurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fournisseur->getId(), $request->request->get('_token'))) {
            $fournisseurRepository->remove($fournisseur);
        }

        return $this->redirectToRoute('app_fournisseur_index', [], Response::HTTP_SEE_OTHER);
    }
}
