<?php

namespace App\Controller\Station;

use App\Entity\SellCarburant;
use App\Form\SellCarburantType;
use App\Repository\SellCarburantRepository;
use App\Repository\SocietyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sell/carburant")
 */
class SellCarburantController extends AbstractController
{
    private SocietyRepository $societyRepository;

    public function __construct(SocietyRepository $societyRepository)
    {
        $this->societyRepository = $societyRepository;

    }

    /**
     * @Route("/", name="app_sell_carburant_index", methods={"GET"})
     */
    public function index(SellCarburantRepository $sellCarburantRepository): Response
    {
        $caisseStation = $this->societyRepository->findBy([],[],[1])[0]->getCaisseStation();
        if (in_array("ROLE_ADMIN", $this->getUser()->getRoles()) ||
            in_array("ROLE_SOUS_ADMIN", $this->getUser()->getRoles()) ||
            in_array("ROLE_STATION_GERANT", $this->getUser()->getRoles())
        ){
            $getAll = $sellCarburantRepository->findAll();
        }else {
            $getAll = $sellCarburantRepository->findBy(['agent'=>$this->getUser()->getFullName()]);
        }
        return $this->render('station-dashboard/sell_carburant/index.html.twig', [
            'sell_carburants' => $getAll,
            'caisseStation' =>number_format($caisseStation, 0, ',','.')??0,
        ]);
    }

    /**
     * @Route("/new", name="app_sell_carburant_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SellCarburantRepository $sellCarburantRepository): Response
    {
        $caisseStation = $this->societyRepository->findBy([],[],[1]);
        $caisseValue = $caisseStation[0]->getCaisseStation();
        $sellCarburant = new SellCarburant();
        $form = $this->createForm(SellCarburantType::class, $sellCarburant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sellCarburant->setAgent($this->getUser()->getFullName());
            $sellCarburant->setCreatedAt(new \DateTimeImmutable());

            $caisseValue+=$sellCarburant->getPrix();
            $caisseStation[0]->setCaisseStation($caisseValue);
            $sellCarburantRepository->add($sellCarburant);
            return $this->redirectToRoute('app_sell_carburant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('station-dashboard/sell_carburant/new.html.twig', [
            'sell_carburant' => $sellCarburant,
            'form' => $form,
            'caisseStation' =>number_format($caisseValue, 0, ',','.')??0,
        ]);
    }

    /**
     * @Route("/{id}", name="app_sell_carburant_show", methods={"GET"})
     */
    public function show(SellCarburant $sellCarburant): Response
    {
        $caisseStation = $this->societyRepository->findBy([],[],[1])[0]->getCaisseStation();
        return $this->render('station-dashboard/sell_carburant/show.html.twig', [
            'sell_carburant' => $sellCarburant,
            'caisseStation' =>number_format($caisseStation, 0, ',','.')??0,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_sell_carburant_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, SellCarburant $sellCarburant, SellCarburantRepository $sellCarburantRepository): Response
    {
        $caisseStation = $this->societyRepository->findBy([],[],[1]);
        $caisseValue = $caisseStation[0]->getCaisseStation();
        $form = $this->createForm(SellCarburantType::class, $sellCarburant);
        $form->handleRequest($request);

        $caisseValue-=$sellCarburant->getPrix();

        if ($form->isSubmitted() && $form->isValid()) {
            $caisseValue+=$sellCarburant->getPrix();
            $caisseStation[0]->setCaisseStation($caisseValue);
            $sellCarburantRepository->add($sellCarburant);
            return $this->redirectToRoute('app_sell_carburant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('station-dashboard/sell_carburant/edit.html.twig', [
            'sell_carburant' => $sellCarburant,
            'form' => $form,
            'caisseStation' =>number_format($caisseValue, 0, ',','.')??0,
        ]);
    }

    /**
     * @Route("/{id}", name="app_sell_carburant_delete", methods={"POST"})
     */
    public function delete(Request $request, SellCarburant $sellCarburant, SellCarburantRepository $sellCarburantRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sellCarburant->getId(), $request->request->get('_token'))) {
            $sellCarburantRepository->remove($sellCarburant);
        }

        return $this->redirectToRoute('app_sell_carburant_index', [], Response::HTTP_SEE_OTHER);
    }
}
