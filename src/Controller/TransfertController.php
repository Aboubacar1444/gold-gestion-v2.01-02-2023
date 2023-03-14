<?php

namespace App\Controller;

use App\Entity\Agency;
use App\Entity\Transfert;
use App\Entity\User;
use App\Form\TransfertcType;
use App\Form\TransfertType;
use App\Repository\AgencyRepository;
use App\Repository\SocietyRepository;
use App\Repository\TransfertRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/transfert")
 * @IsGranted("ROLE_USER")
 */
class TransfertController extends AbstractController
{
    

    /**
     * @Route("/new", name="transfert")
     */
    public function index(Request $request, AgencyRepository $agencyRepository, SocietyRepository $societyRepository, UserRepository $userRepository): Response
    {
        function generateCode($limit){
            $code = '';
            for($i = 0; $i < $limit; $i++) { $code .= mt_rand(1, 9); }
            return $code;
        }
        $society=$societyRepository->findAll();
        

        $transfert= new Transfert();
        if ($request->get('id') && $request->get('cl')) {
            $form = $this->createForm(TransfertcType::class, $transfert);
            $client=$userRepository->findOneBy(['id'=>$request->get('id'), 'username'=>$request->get('cl')]);
            $clientcaisse=$client->getSolde();
        }
        elseif (!$request->get('id') && !$request->get('cl')) {
            $client=false;
            $clientcaisse=false;
            $form = $this->createForm(TransfertType::class, $transfert);
        }
        else{
            $this->addFlash("error", "Attention ce client n'existe pas!");
            return $this->redirectToRoute('dashboard');
        }
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            if ($this->getUser()->getAgency()) {
                $agencysender=$agencyRepository->findOneBy(['name'=>$this->getUser()->getAgency()->getName()]);
            }else{
                $agencysender=false;
            }
            
            if ($client){
                $transfert->setClient($client);
                $clientcaisse-=$transfert->getMontant();
                $client->setSolde($clientcaisse);
                
                if ($this->getUser()->getAgency()) {
                    $agency=$this->getUser()->getAgency()->getName();
                    $transagency=$agencysender->getCaisse();
                    $transagency+=$transfert->getMontant();
                    $transfert->setAgency($agency);
                    $agencysender->setCaisse($transagency);
                    foreach ($society as $s) {
                        $caisse=$s->getCaisse();
                        $caisse+=$transfert->getMontant();
                        $s->setCaisse($caisse);
                    }
                }else{
                    foreach ($society as $s) {
                        $caisse=$s->getCaisse();
                        $caisse+=$transfert->getMontant();
                        $s->setCaisse($caisse);
                    }
                }
                if ($transfert->getFrais() !== null) {
                    $frais=$transfert->getFrais();
                    if ($frais !== 0 ) {
                        if($request->get('status')){
                            $status=$request->get('status');
                            $statusclient=$request->get('statusclient');
                            if (!$statusclient) {
                               $statusclient="NON"; 
                            }
                            $transfert->setPaid($status);
                        }
                        else {
                            $status="NON";
                            $transfert->setPaid($status);
                        }
                        if ($status == "OUI") {
                            if ($statusclient == "OUI") {
                                $clientcaisse-=$frais;
                                $client->setSolde($clientcaisse);
                            }
                            foreach ($society as $s) {
                                $caisse=$s->getCaisse();
                                $caisse+=$frais;
                                $s->setCaisse($caisse);
                            }
                        }
                    }
                   
                }
  
            }
            elseif(!$client) {
                $client=false;
                
                if($this->getUser()->getAgency()) {
                    $agency=$this->getUser()->getAgency()->getName();
                    $transagency=$agencysender->getCaisse();
                    $transagency+=$transfert->getMontant();
                    $agencysender->setCaisse($transagency);
                    $transfert->setAgency($agency);
                    foreach ($society as $s) {
                        $caisse=$s->getCaisse();
                        $caisse+=$transfert->getMontant();
                        $s->setCaisse($caisse);
                    }
                }else{
                    foreach ($society as $s) {
                        $caisse=$s->getCaisse();
                        $caisse+=$transfert->getMontant();
                        $s->setCaisse($caisse);
                    }
                }
                if($transfert->getFrais() !== null) {
                    $frais=$transfert->getFrais();
                    if ($frais !== 0 ) {
                        if($request->get('status')){
                          $status=$request->get('status');
                          $transfert->setPaid($status);
                        }else {
                            $status="NON";
                            $transfert->setPaid($status);
                        }
                        if ($status == "OUI") {
                            foreach ($society as $s) {
                                $caisse=$s->getCaisse();
                                $caisse+=$frais;
                                $s->setCaisse($caisse);
                            }
                        }
                        
                    }
                }
            }
            else{
                $this->addFlash("error", "Attention ce client n'existe pas!");
                return $this->redirectToRoute('dashboard');
            }
            $transfert->setAgent($this->getUser()->getFullname());
            $transfert->setSecretid(generateCode(10));
            $transfert->setSentAt(new \DateTimeImmutable());
            $transfert->setTransagency($transfert->getTransagency());
            
            // dd($transfert);
            $this->getDoctrine()->getManager()->persist($transfert);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", "Le transfert d'argent a été effectué avec succès.");
            return $this->redirectToRoute('sent',['id'=> $transfert->getId()],Response::HTTP_SEE_OTHER);

        }
        return $this->render('transfert/index.html.twig', [
            'society' => $societyRepository->findAll(),
            'form' => $form->createView(),  
            'client'=>$client,          
        ]);
    }
    /**
     * @Route("/sent/{id}", name="sent")
     */
    public function sent(Request $request, Transfert $transfert, SocietyRepository $societyRepository): Response
    {
        return $this->render('transfert/sent.html.twig', [
            'society'=>$societyRepository->findAll(),
            'transfert'=>$transfert,
        ]);
    }

    /**
     * @Route("/getagency/{id}", name="getagency")
     */
    public function getagency(Request $request, Agency $agency): Response
    {
        $solde=$agency->getCaisse();
        return new JsonResponse($solde);
    }

    /**
     * @Route("/receive/{secretid}/{id}", name="receive")
     */
    public function receive(Request $request, Transfert $transfert, SocietyRepository $societyRepository): Response
    {
        $society=$societyRepository->findAll();
        if ($request->get('confirm') == 'gotit') {
            $transfert->setTransagent($this->getUser()->getFullname());
            $transfert->setReceveAt(new \DateTimeImmutable());
            $transfert->setFacture("Ok");
            if ($this->getUser()->getAgency()) {
                $agencycaisse=$transfert->getTransagency()->getCaisse();
                $agencycaisse-=$transfert->getMontant();
                $transfert->getTransagency()->setCaisse($agencycaisse);
            }
            
            
            foreach ($society as $s) {
                $caisse=$s->getCaisse();
                $caisse-=$transfert->getMontant();
                $s->setCaisse($caisse);
            }
            if ($transfert->getClient()) {
                if($transfert->getFrais() !== null) {
                    $frais=$transfert->getFrais();
                    if ($frais !== 0 ) {
                        if($transfert->getPaid()){
                          $status=$transfert->getPaid();
                        }
                        if ($status == "NON") {
                            $newmontant=$transfert->getMontant()-$transfert->getFrais();
                            $transfert->setMontant($newmontant);
                            foreach ($society as $s) {
                                $caisse=$s->getCaisse();
                                $caisse+=$frais;
                                $s->setCaisse($caisse);
                            }
                        }  
                    }  
                }
                else{
                    $newmontant=false;
                }
            }
            elseif(!$transfert->getClient()){
                if($transfert->getFrais() !== null) {
                    $frais=$transfert->getFrais();
                    if ($frais !== 0 ) {
                        if($transfert->getPaid()){
                          $status=$transfert->getPaid();
                        }
                        if ($status == "NON") {
                            $newmontant=$transfert->getMontant()-$transfert->getFrais();
                            $transfert->setMontant($newmontant);
                            foreach ($society as $s) {
                                $caisse=$s->getCaisse();
                                $caisse+=$frais;
                                $s->setCaisse($caisse);
                            }
                        } 
                        else{
                            $newmontant=false;
                        } 
                    }
                }
            }
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", "Rétrait effectué avec succès.");
            return $this->redirectToRoute('receved',['id'=> $transfert->getId(), 'newamount'=>$newmontant],Response::HTTP_SEE_OTHER);
        }
        return $this->render('transfert/receve.html.twig', [
            'society'=>$societyRepository->findAll(),
            'transfert'=>$transfert,
        ]);
    }

    /**
     * @Route("/receved/{id}", name="receved")
     */
    public function receved(Request $request, Transfert $transfert, SocietyRepository $societyRepository): Response
    {
        if ($request->get('newamount')) {
            $newamount=$request->get('newamount');
        }else{
            $newamount=false;
        }
        return $this->render('transfert/receved.html.twig', [
            'society'=>$societyRepository->findAll(),
            'transfert'=>$transfert,
            'newamount'=>$newamount,
        ]);
    }

}
