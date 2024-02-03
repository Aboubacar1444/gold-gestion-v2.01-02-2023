<?php

namespace App\Controller;

use App\Repository\BuyCarburantRepository;
use App\Repository\SellCarburantRepository;
use App\Repository\SocietyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StationController extends AbstractController
{
    private SocietyRepository $societyRepository;
    private SellCarburantRepository $sellCarburantRepository;
    private BuyCarburantRepository $buyCarburantRepository;

    public function __construct(SocietyRepository $societyRepository, SellCarburantRepository $sellCarburantRepository, BuyCarburantRepository $buyCarburantRepository)
    {
        $this->societyRepository = $societyRepository;
        $this->sellCarburantRepository = $sellCarburantRepository;
        $this->buyCarburantRepository = $buyCarburantRepository;

    }
    /**
     * @Route("/station", name="app_station_dashboard")
     */
    public function index(Request  $request): Response
    {
        $caisseStation = $this->societyRepository->findBy([],[],[1])[0]->getCaisseStation();
        $firstDate =$request->get('firstDate')?: new \DateTimeImmutable(date('Y-m-d 00:00:00'));
        $secondDate =$request->get('secondDate')?: new \DateTimeImmutable(date('Y-m-d 23:59:00'));


        if (in_array("ROLE_STATION_AGENT", $this->getUser()->getRoles())) {
            $getAllSellCarburant = $this->sellCarburantRepository->findBetweenDates($firstDate, $secondDate);
        }else {
            $getAllSellCarburant = $this->sellCarburantRepository->findBetweenDates($firstDate, $secondDate);
        }
        $getAllBuyCarburant = $this->buyCarburantRepository->findBetweenDates($firstDate, $secondDate);


        return $this->render("station-dashboard/index.html.twig",[
            'caisseStation' =>number_format($caisseStation, 0, ',','.')??0,
            'sellCarburants' =>$getAllSellCarburant,
            'buyCarburants' =>$getAllBuyCarburant,
        ]);
    }
}
