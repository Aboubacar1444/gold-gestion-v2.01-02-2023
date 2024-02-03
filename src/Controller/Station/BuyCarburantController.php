<?php

namespace App\Controller\Station;

use App\Entity\BuyCarburant;
use App\Form\BuyCarburantType;
use App\Repository\BuyCarburantRepository;
use App\Repository\SocietyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/buy/carburant")
 */
class BuyCarburantController extends AbstractController
{
    private SocietyRepository $societyRepository;

    public function __construct(SocietyRepository $societyRepository)
    {
        $this->societyRepository = $societyRepository;

    }
    /**
     * @Route("/", name="app_buy_carburant_index", methods={"GET"})
     */
    public function index(BuyCarburantRepository $buyCarburantRepository): Response
    {
        $caisseStation = $this->societyRepository->findBy([],[],[1])[0]->getCaisseStation();
        return $this->render('station-dashboard/buy_carburant/index.html.twig', [
            'buy_carburants' => $buyCarburantRepository->findAll(),
            'caisseStation' =>number_format($caisseStation, 0, ',','.')??0,

        ]);
    }

    /**
     * @Route("/new", name="app_buy_carburant_new", methods={"GET", "POST"})
     */
    public function new(Request $request, BuyCarburantRepository $buyCarburantRepository): Response
    {
        $caisseStation = $this->societyRepository->findBy([],[],[1]);
        $caisseValue = $caisseStation[0]->getCaisseStation();
        $buyCarburant = new BuyCarburant();

        $form = $this->createForm(BuyCarburantType::class, $buyCarburant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $buyCarburant->setUser($this->getUser()->getFullName());
            $buyCarburant->setCreatedAt(new \DateTimeImmutable());

            $caisseValue-=$buyCarburant->getPrix();
            $caisseStation[0]->setCaisseStation($caisseValue);

            $buyCarburantRepository->add($buyCarburant);


            return $this->redirectToRoute('app_buy_carburant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('station-dashboard/buy_carburant/new.html.twig', [
            'buy_carburant' => $buyCarburant,
            'form' => $form,
            'caisseStation' =>number_format($caisseValue, 0, ',','.')??0,
        ]);
    }

    /**
     * @Route("/{id}", name="app_buy_carburant_show", methods={"GET"})
     */
    public function show(BuyCarburant $buyCarburant): Response
    {
        $caisseStation = $this->societyRepository->findBy([],[],[1])[0]->getCaisseStation();
        return $this->render('station-dashboard/buy_carburant/show.html.twig', [
            'buy_carburant' => $buyCarburant,
            'caisseStation' =>number_format($caisseStation, 0, ',','.')??0,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_buy_carburant_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, BuyCarburant $buyCarburant, BuyCarburantRepository $buyCarburantRepository): Response
    {
        $caisseStation = $this->societyRepository->findBy([],[],[1]);
        $caisseValue = $caisseStation[0]->getCaisseStation();
        $form = $this->createForm(BuyCarburantType::class, $buyCarburant);
        $form->handleRequest($request);

        $caisseValue-=$buyCarburant->getPrix();
        if ($form->isSubmitted() && $form->isValid()) {
            $caisseValue+=$buyCarburant->getPrix();
            $caisseStation[0]->setCaisseStation($caisseValue);
            $buyCarburantRepository->add($buyCarburant);
            return $this->redirectToRoute('app_buy_carburant_index', [], Response::HTTP_SEE_OTHER);
        }

        $caisseStation = $this->societyRepository->findBy([],[],[1])[0]->getCaisseStation();
        return $this->renderForm('station-dashboard/buy_carburant/edit.html.twig', [
            'buy_carburant' => $buyCarburant,
            'form' => $form,
            'caisseStation' =>number_format($caisseValue, 0, ',','.')??0,
        ]);
    }

    /**
     * @Route("/{id}", name="app_buy_carburant_delete", methods={"POST"})
     */
    public function delete(Request $request, BuyCarburant $buyCarburant, BuyCarburantRepository $buyCarburantRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$buyCarburant->getId(), $request->request->get('_token'))) {
            $buyCarburantRepository->remove($buyCarburant);
        }

        return $this->redirectToRoute('app_buy_carburant_index', [], Response::HTTP_SEE_OTHER);
    }
}
