<?php

namespace App\Controller;

use App\Entity\Operations;
use App\Form\OperationDubaiType;
use App\Form\OperationEchangeType;
use App\Form\OperationLotType;
use App\Form\OperationProductType;
use App\Form\OperationTempType;
use App\Repository\OperationsRepository;
use App\Repository\SocietyRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/moreoperation")
 * @IsGranted("ROLE_USER")
 */
class SecondController extends AbstractController
{
    /**
     * @Route("/temp/product/{transact}", name="operationproduct")
     */
    public function OperationsProduct(Request $request, SocietyRepository $societyRepository): Response
    {
        $em= $this->getDoctrine()->getManager();
        $transact=$request->get('transact');
        
        $operation = new Operations();
        $form = $this->createForm(OperationProductType::class, $operation);
        $form->handleRequest($request);
        
       
            if ($form->isSubmitted() && $form->isValid()) { 
                $operation=$form->getData();
                $operation->setCreatedAt(new \DateTime(date('Y-m-d')));
                $operation->setTime(date('H:i:s'));
                $operation->setAgent($this->getUser()->getFullname());
                $operation->setBase(0);
                if ($this->getUser()->getAgency()) {
                    $operation->setAgency($this->getUser()->getAgency());
                }
                $operation->setType($transact);
                $em->persist($operation);
                $em->flush();
                return new JsonResponse([ $operation->getTempclient(), $operation->getType(), date_format($operation->getCreatedAt(), 'Y-m-d 00:00:00'), $operation->getTel() ]);
            }
            
        

        $society=$societyRepository->findAll();
        return $this->render('dashboard/operationsproduct.html.twig', [
            'society'=>$society,
            'transact'=>$transact,
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/facturationproduct/{id}/{type}/{date}", name="facturationproduct")
     */
    public function FacturationProduct(Request $request, OperationsRepository $operationsRepository, UserRepository $userRepository, SocietyRepository $societyRepository): Response
    {
        $em= $this->getDoctrine()->getManager();
        $type=$request->get('type');
        $date=$request->get('date');
        $date=new \DateTime($date);
        $id=$request->get('id');
        $user=$operationsRepository->findOneBy(
            ['tempclient'=>$id,'type'=>$type, 'createdAt'=>$date, 'facture'=>null],
        );
        
        
       
        
        $operations=$operationsRepository->findBy(
            ['tempclient'=>$id, 'type'=>$type, 'createdAt'=>$date, 'facture'=>null],
            ['createdAt'=>'ASC'],
        );
        
        
        $setnumero=$operationsRepository->setNumero($type);
        
        /* Attribution de numero de facture ou reçu */
        if($setnumero){
            foreach ($setnumero as $k) {
                $numero=$k["numero"];
                $numero+=1;
            }
        }else{
            if ($operations) {
                foreach ($operations as $v) {
                    $numero=$v->getNumero();
                    $numero+=1;
                }
            }
        }
        /* Calcule plus enregistrement */
        
        $totalmontant=0;
        $total=0;
        $totalqte=0;
        $totalprixu=0;
       
        $society=$societyRepository->findAll();
        if($type == "Achat de produit" || $type == "Vente de produit"){
            $caisse=0;
            
            $barre=count($operations);
            
            if ($operations) {
                if ($this->getUser()->getAgency()) {
                    $agencycaisse=$this->getUser()->getAgency()->getCaisse();
                
                }else{
                    $agencycaisse=false;
                }
                
                foreach ($operations as $c){
                    $agent=$c->getAgent();
                    $totalmontant+=$c->getTotalm();
                    $totalqte+=$c->getQte();
                    $totalprixu+=$c->getPrixu();
                    $total=$totalmontant;
                    
                    // $c->setTotalm($totalmontant);
                    $c->setTotal($total);
                    $c->setNumero($numero);
                    $c->setFacture('Ok');
                   
                   
                }
                foreach ($society as $s) {
                        
                   /* $caisse=$s->getCaisse();
                    if($type == "Achat de produit"){
                        
                        if ($agencycaisse) {
                            $agencycaisse-=$total;
                            $caisse=$caisse - $total;
                            $s->setCaisse($caisse);
                            $this->getUser()->getAgency()->setCaisse($agencycaisse);
                        }else
                        {
                            $caisse=$caisse - $total;
                            $s->setCaisse($caisse);
                        }
                        
                        
                        $em->flush();
                        
                    }
                    elseif ($type == "Vente de produit") {
                        
                        if ($agencycaisse) {
                            $agencycaisse+=$total;
                            $caisse=$caisse + $total;
                            $this->getUser()->getAgency()->setCaisse($agencycaisse);
                            $s->setCaisse($caisse);
                        }else{
                            $caisse=$caisse + $total;
                            $s->setCaisse($caisse);
                        }
                        
                        $em->flush();
                    }   */
                }
            }
        }
        $em->flush();
        $this->addFlash("success", "Facture enregistrée!");
        
        
        
        return $this->render('dashboard/facturationproduct.html.twig', [
            'society'=>$society,
            'type'=>$type,
            'client'=>$user,
            'agent'=>$this->getUser()->getFullname(),
            'operations'=>$operations,
            'date'=>$date,
            'numero'=>$numero,
            'totalmontant'=>$totalmontant,
            'total'=>$total,
            'totalqte'=>$totalqte,
            'totalprixu'=>$totalprixu,
            'agent'=>$agent,
            'barre'=>$barre,
        ]);
    }
    
    /**
     * @Route("/temp/echange/{transact}", name="operationechange")
     */
    public function OperationsEchange(Request $request, SocietyRepository $societyRepository): Response
    {
        $em= $this->getDoctrine()->getManager();
        $transact=$request->get('transact');
        
        $operation = new Operations();
        $form = $this->createForm(OperationEchangeType::class, $operation);
        $form->handleRequest($request);
        
       
            if ($form->isSubmitted() && $form->isValid()) { 
                $operation=$form->getData();
                $operation->setCreatedAt(new \DateTime(date('Y-m-d')));
                $operation->setTime(date('H:i:s'));
                $operation->setAgent($this->getUser()->getFullname());
                $operation->setBase(0);
                if ($this->getUser()->getAgency()) {
                    $operation->setAgency($this->getUser()->getAgency());
                }
                $operation->setType($transact);
                $em->persist($operation);
                $em->flush();
                return new JsonResponse([ $operation->getTempclient(), $operation->getType(), date_format($operation->getCreatedAt(), 'Y-m-d 00:00:00'), $operation->getTel(), $operation->getAvance() ]);
            }
            
        

        $society=$societyRepository->findAll();
        return $this->render('dashboard/operationsechange.html.twig', [
            'society'=>$society,
            'transact'=>$transact,
            'form'=>$form->createView(),
        ]);
    }
    /**
     * @Route("/temp/echangef/{transact}", name="operationechangef")
     */
    public function OperationsEchangeF(Request $request, SocietyRepository $societyRepository): Response
    {
        $em= $this->getDoctrine()->getManager();
        $transact=$request->get('transact');
        
        $operation = new Operations();
        $form = $this->createForm(OperationEchangeType::class, $operation);
        $form->handleRequest($request);
        
       
            if ($form->isSubmitted() && $form->isValid()) { 
                $operation=$form->getData();
                $operation->setCreatedAt(new \DateTime(date('Y-m-d')));
                $operation->setTime(date('H:i:s'));
                $operation->setAgent($this->getUser()->getFullname());
                $operation->setBase(0);
                if ($this->getUser()->getAgency()) {
                    $operation->setAgency($this->getUser()->getAgency());
                }
                $operation->setType($transact);
                $em->persist($operation);
                $em->flush();
                return new JsonResponse([ $operation->getTempclient(), $operation->getType(), date_format($operation->getCreatedAt(), 'Y-m-d 00:00:00'), $operation->getTel(), $operation->getAvance() ]);
            }
            
        

        $society=$societyRepository->findAll();
        return $this->render('dashboard/operationsechangef.html.twig', [
            'society'=>$society,
            'transact'=>$transact,
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/facturationechange/{id}/{type}/{date}", name="facturationechange")
     */
    public function FacturationEchange(Request $request, OperationsRepository $operationsRepository, UserRepository $userRepository, SocietyRepository $societyRepository): Response
    {
        $em= $this->getDoctrine()->getManager();
        $type=$request->get('type');
        $date=$request->get('date');
        $date=new \DateTime($date);
        $id=$request->get('id');
        $newop = true;
        $user=$operationsRepository->findOneBy(
            ['tempclient'=>$id,'type'=>$type, 'createdAt'=>$date, 'facture'=>null],
        );
        if(!$user){
            $user=$operationsRepository->findOneBy(
                ['tempclient'=>$id,'type'=>$type, 'createdAt'=>$date, 'facture'=>"Ok"],
            );
            $newop=false;   
        }
        
        $operations=$operationsRepository->findBy(
            ['tempclient'=>$id, 'type'=>$type, 'facture'=>null],
            ['createdAt'=>'ASC'],
        );
        if(!$operations){
            $operations=$operationsRepository->findBy(
                ['tempclient'=>$id, 'type'=>$type, 'facture'=>"Ok"],
                ['createdAt'=>'ASC'],
            );
            $newop=false; 
        }
        
        $numero = false;
        $setnumero=$operationsRepository->setNumero($type);
            if ($newop) {  
                foreach ($operations as $v) {
                    if($setnumero){
                        foreach ($setnumero as $k) {
                            $numero=$k["numero"];
                            $numero+=1;
                        }
                    }else{
                        if ($operations) {
                            $numero=$v->getNumero();
                            $numero+=1;
                        }
                    }   
                }
            }
        
        /* Attribution de numero de facture ou reçu */
        /*if($setnumero){
            foreach ($setnumero as $k) {
                $numero=$k["numero"];
                $numero+=1;
            }
        }else{
            if ($operations) {
                foreach ($operations as $v) {
                    if (!$v->getFacture()) {
                        $numero=$v->getNumero();
                        $numero+=1;
                    }
                }
            }
        }*/
        /* Calcule plus enregistrement */
        
        $totalmontant=0;
        $montant=0;
        $total=0;
        $avance = false;
        $valid = false;
        
        $society=$societyRepository->findAll();
        if($type == "DF" || $type == "EF" || $type == "FD" || $type == "FE"){
            $caisse=0;
            $agencycaisse =false;
            
            if ($operations) {
                if ($this->getUser()->getAgency()) {
                    $agencycaisse=$this->getUser()->getAgency()->getCaisse();
                }
                
                foreach ($operations as $c){
                    $agent=$c->getAgent();
                    $totalmontant+=$c->getTotalm();
                    $montant+=$c->getMontant();
                    $total=$totalmontant;
                    $c->getValid() ? $avance = true: $avance;
                    if($c->getAvance()) $avance = true;
                    // $c->setTotalm($totalmontant);
                    else $c->setTotal($total);
                    if(!$c->getFacture()) $c->setNumero($numero);
                    $c->setFacture('Ok');
 
                }
                foreach ($society as $s) {    
                    $caisse=$s->getCaisse();
                    if($type == "DF"){
                        foreach ($operations as $v) {
                            if($newop){
                                $dollar=$s->getDollar();
                                if ($agencycaisse) {
                                    $agencycaisse+=$total;
                                    $caisse=$caisse + $total;
                                    $dollar-=$montant;
                                    $s->setCaisse($caisse);
                                    $s->setDollar($dollar);
                                    $this->getUser()->getAgency()->setCaisse($agencycaisse);
                                }else
                                {
                                    $caisse=$caisse + $total;
                                    $dollar-=$montant;
                                    $s->setCaisse($caisse);
                                    $s->setDollar($dollar);
                                }
                            }else{
                                if ($request->get("newavance")){
                                    $newavance = $v->getAvance();
                                    $newavance += $request->get("newavance");
                                    $v->setAvance($newavance);
                                    $v->setTotal($request->get("reliquat"));
                                    $v->setCreatedAt(new \DateTime(date('Y-m-d 00:00:00')));
                                    $v->setTime(date("H:i:s")); 
                                }
                                if ($request->get("validated") == true) {
                                    $v->setValid(true);
                                }
                            }
                        }
                        
                        if ($request->get('valid') == true) {
                            $this->addFlash("success", "Facture enregistrée!");
                            $em->flush();
                            foreach ($operations as $v) {
                                $res=$v->getAvance();
                            }
                            
                            return new JsonResponse($res);
                        }
                        
                    }
                    
                    if ($type == "EF") {
                        foreach ($operations as $v ) {
                           if($newop){
                               $euro=$s->getEuro();
                               if ($agencycaisse) {
                                   $agencycaisse+=$total;
                                   $caisse=$caisse + $total;
                                   $euro-=$montant;
                                   $s->setEuro($euro);
                                   $this->getUser()->getAgency()->setCaisse($agencycaisse);
                                   $s->setCaisse($caisse);
                               }else{
                                   $caisse=$caisse + $total;
                                   $euro-=$montant;
                                   $s->setCaisse($caisse);
                                   $s->setEuro($euro);
                               }
                            }else{
                                if ($request->get("newavance")){
                                    $newavance = $v->getAvance();
                                    $newavance += $request->get("newavance");
                                    $v->setAvance($newavance);
                                    $v->setTotal($request->get("reliquat"));
                                    $v->setCreatedAt(new \DateTime(date('Y-m-d 00:00:00')));
                                    $v->setTime(date("H:i:s")); 
                                }
                                if ($request->get("validated") == true) {
                                    $v->setValid(true);
                                }
                            }
                        }
                        
                        if ($request->get('valid') == true) {
                            $this->addFlash("success", "Facture enregistrée!");
                            $em->flush();
                            foreach ($operations as $v) {
                                $res=$v->getAvance();
                            }
                            
                            return new JsonResponse($res);
                        }
                    }
                    if ($type == "FD") {
                        foreach ($operations as $v) {
                            if($newop){
                                $dollar=$s->getDollar();
                                if ($agencycaisse) {
                                    $agencycaisse-=$total;
                                    $caisse=$caisse - $total;
                                    $dollar+=$montant;
                                    $s->setCaisse($caisse);
                                    $s->setDollar($dollar);
                                    $this->getUser()->getAgency()->setCaisse($agencycaisse);
                                }else
                                {
                                    $caisse=$caisse - $total;
                                    $dollar+=$montant;
                                    $s->setCaisse($caisse);
                                    $s->setDollar($dollar);
                                }
                            }else{
                                if ($request->get("newavance")){
                                    $newavance = $v->getAvance();
                                    $newavance += $request->get("newavance");
                                    $v->setAvance($newavance);
                                    $v->setTotal($request->get("reliquat"));
                                    $v->setCreatedAt(new \DateTime(date('Y-m-d 00:00:00')));
                                    $v->setTime(date("H:i:s")); 
                                }
                                if ($request->get("validated") == true) {
                                    $v->setValid(true);
                                }
                            }
                        }
                        
                        if ($request->get('valid') == true) {
                            $this->addFlash("success", "Facture enregistrée!");
                            $em->flush();
                            foreach ($operations as $v) {
                                $res=$v->getAvance();
                            }
                            
                            return new JsonResponse($res);
                        }
                    }
                    if ($type == "FE"){
                        foreach ($operations as $v) {
                            if($newop){
                                $euro=$s->getEuro();
                                if ($agencycaisse) {
                                    
                                    $agencycaisse-=$total;
                                    $caisse=$caisse - $total;
                                    $euro+=$montant;
                                    $s->setEuro($euro);
                                    $this->getUser()->getAgency()->setCaisse($agencycaisse);
                                    $s->setCaisse($caisse);
                                }else{
                                    $caisse=$caisse - $total;
                                    $euro+=$montant;
                                    $s->setCaisse($caisse);
                                    $s->setEuro($euro);
                                }
                            }else{
                                if ($request->get("newavance")){
                                    $newavance = $v->getAvance();
                                    $newavance += $request->get("newavance");
                                    $v->setAvance($newavance);
                                    $v->setTotal($request->get("reliquat"));
                                    $v->setCreatedAt(new \DateTime(date('Y-m-d 00:00:00')));
                                    $v->setTime(date("H:i:s")); 
                                    
                                }
                                if ($request->get("validated") == true) {
                                    $v->setValid(true);
                                }
                                
                            }
                        }
                        
                        if ($request->get('valid') == true) {
                            $this->addFlash("success", "Facture enregistrée!");
                            $em->flush();
                            foreach ($operations as $v) {
                                $res=$v->getAvance(); 
                            }
                            return new JsonResponse($res);
                        }
                    } 
                }
            }
        }
        #$em->flush();
        
        
        
        return $this->render('dashboard/facturationechange.html.twig', [
            'society'=>$society,
            'type'=>$type,
            'client'=>$user,
            'agent'=>$this->getUser()->getFullname(),
            'operations'=>$operations,
            'date'=>$date,
            'numero'=>$numero ? $numero : $user->getNumero(),
            'montant'=>$montant,
            'totalmontant'=>$totalmontant,
            'total'=>$total,
            'agent'=>$agent,
            'avance'=>$avance,
            'valid'=>$valid,
            'newav'=>$request->get("newav"),
            'validated'=>$request->get("validated"),
        ]);
    }

    /**
     * @Route("/operationlot/{transact}", name="operationlot")
     */
    public function OperationsLot(Request $request, OperationsRepository $operationsRepository, SocietyRepository $societyRepository, UserRepository $userRepository): Response
    {
        $em= $this->getDoctrine()->getManager();
        
        // $user=$userRepository->findOneBy(['id'=>$request->get('id'), 'type'=>$request->get('type')]);
        // if (!$user) {
        //     $this->addFlash("error", "Attention ce client n'existe pas!");
        //     return $this->redirectToRoute('dashboard');
        // }else{
            $transact=$request->get('transact');
        // }
        if ($request->get('numero')) {
            $next=$request->get('numero');
            $ops= $operationsRepository->findOneBy(['facture'=>'Ok','numero'=>$request->get('numero')],['id'=>'DESC']);
        }else {
            $ops=false;
            $next=false;
        }


        $operation = new Operations();
        $form = $this->createForm(OperationLotType::class, $operation);
        $form->handleRequest($request);
        
        $setnumero=$operationsRepository->setNumero($transact);
        
            if($form->isSubmitted() && $form->isValid()) { 
                $operation=$form->getData();
                $operation->setCreatedAt(new \DateTime(date('Y-m-d')));
                $operation->setTime(date('H:i:s'));
                $operation->setAgent($this->getUser()->getFullname());
                $operation->setBase(0);
                
                if ($this->getUser()->getAgency()) {
                    $operation->setAgency($this->getUser()->getAgency());
                }
                $operation->setType($transact);
                // $operation->setFacture("Ok");
                // $operation->setClient($user);
                //     if($setnumero){
                //         foreach ($setnumero as $k) {
                //             $numero=$k["numero"];
                //             $numero+=1;
                //         }
                //     }
                //     else{
                //         $operations=$operationsRepository->findBy(
                //             ['type'=>$transact, 'facture'=>"Ok"],
                //             ['createdAt'=>'ASC'],
                //         );
                //         if ($operations) {
                //             foreach ($operations as $v) {
                //                 $numero=$v->getNumero();
                //                 $numero+=1;
                //             }
                //         }else  $numero = 1;
                //     }  
                // if ($ops) {
                //     $operation->setNumero($ops->getNumero());
                    
                // }else{
                //     $operation->setNumero($numero);
                    
                // }
                
                $em->persist($operation);
                $em->flush();
               
                return new JsonResponse([$operation->getType(), $operation->getAvdollar(),$operation->getTaux(), date_format($operation->getCreatedAt(), 'Y-m-d 00:00:00'), $operation->getNumero() ]);
            }
        $society=$societyRepository->findAll();
        return $this->render('dashboard/operationlot.html.twig', [
            'society'=>$society,
            // 'client'=>$user,
            'transact'=>$transact,
            'form'=>$form->createView(),
            'ops'=>$ops,
            'next'=>$next,
        ]);
    }

    /**
     * @Route("/temp/opslotdubai/{transact}", name="operationlotdubai")
     */
    public function OperationLotDubai(Request $request, SocietyRepository $societyRepository, OperationsRepository $operationsRepository): Response
    {
        $em= $this->getDoctrine()->getManager();
        $transact=$request->get('transact');
        
        $caisse=0;
        $society=$societyRepository->findAll();
        $operation = new Operations();
        $form = $this->createForm(OperationDubaiType::class, $operation);
        $form->handleRequest($request);
        $setnumero=$operationsRepository->setNumero($transact);
       
            if ($form->isSubmitted() && $form->isValid()) { 
                $operation=$form->getData();
                $operation->setCreatedAt(new \DateTime(date('Y-m-d')));
                $operation->setTime(date('H:i:s'));
                $operation->setAgent($this->getUser()->getFullname());
                $operation->setBase(0);
                if ($this->getUser()->getAgency()) {
                    $operation->setAgency($this->getUser()->getAgency());
                }
                $operation->setType($transact);
                $operation->setFacture('Ok');
                $em->persist($operation);
                $em->flush();
                foreach ($society as $k) {
                    $caisse = ($k->getDollar() - ($operation->getMontant()+$operation->getAvdollar()));
                    $k->setDollar($caisse);
                }
                return new JsonResponse([ $operation->getTempclient(), $operation->getType(), date_format($operation->getCreatedAt(), 'Y-m-d 00:00:00'), $operation->getTel(), $operation->getNumero(), $operation->getTotalm() ]);
            }
            
                foreach ($society as $k) {
                    $caisse= $k->getDollar();
                }
           
        return $this->render('dashboard/operationslotdubai.html.twig', [
            'society'=>$society,
            'transact'=>$transact,
            'caisse'=>$caisse,
            'form'=>$form->createView(),
        ]);
    }
    
    /**
     * @Route("/facturationtemplot/{type}/{numero}", name="facturationtemplot")
     */
    public function FacturationTempLot(Request $request, OperationsRepository $operationsRepository, UserRepository $userRepository, SocietyRepository $societyRepository): Response
    {
        $em= $this->getDoctrine()->getManager();
        $type=$request->get('type');
        $numero=$request->get('numero');
        $id=$request->get('id');
        $see=$request->get('see');
        $delete=$request->get('delete');
        $date=$request->get('date');
        $date=new \DateTime($date);
           
        $user=$userRepository->findOneBy(['id'=>$id]);
        $operations=$operationsRepository->findBy(
            ['type'=>$type, 'facture'=>null, 'numero'=>$numero],
            ['id'=>'ASC']
        );
        if(!$operations){
            $operations=$operationsRepository->findBy(
                ['type'=>$type,  'facture'=>'Ok', 'numero'=>$numero],
                ['id'=>'ASC']
            );
        }
        
        $dollar=0;
        $solde=0;
        $comis=0;
        $montant=0;
        $avdollar=0;
        $society=$societyRepository->findAll();
        if (!$see) {
            if ($type=="Lot") {
                $setnumero=$operationsRepository->setNumero($type);
                // if($setnumero){
                //     foreach ($setnumero as $k) {
                //         $numero=$k["numero"];
                //         $numero+=1;
                //     }
                // }else{
                //     if ($operations) {
                //         foreach ($operations as $v) {
                //             $numero=$v->getNumero();
                //             $numero+=1;
                //         }
                //     }
                // }
                foreach ($society as $s) {
                    $dollar=$s->getDollar();
                    foreach ($operations as $k) {
                        $k->setTotal($k->getAvdollar());
                        $avdollar+=$k->getAvdollar();
                        $k->setNumero($numero);
                        $k->setFacture('Ok');
                    }
                   $dollar+=$avdollar; 
                   $s->setDollar($dollar);
                }
                $totalm=false;
                
                if ($request->get('valid') == true) {
                    $em->flush();
                    return new JsonResponse($request->get('valid'));
                    $this->addFlash("success", "Facture enregistrée!");
                }
            }else{
                foreach ($society as $s) {
                    $dollar=$s->getDollar();
                    foreach ($operations as $o) {
                        $montant+=$o->getMontant();
                        $comis+=$o->getAvdollar();
                        $totalm=$o->getTotalm();
                        $solde=$montant;
                        $dollar=$totalm;
                        $o->setTotal($solde);
                        $o->setValid(true);
                    }
                    $s->setDollar($dollar);
                }
                if($request->get('valid') == true) {
                    $em->flush();
                    return new JsonResponse($request->get('valid'));
                    $this->addFlash("success", "Facture enregistrée!");
                }
            }
        }else $totalm = false;
        if ($delete) {
            $dollar=0;
            $supprdollar=0;
            $operations=$operationsRepository->findBy(
                ['client'=>$id, 'type'=>$type,  'facture'=>'Ok', 'numero'=>$numero],
            );
            
            foreach ($operations as $o) {
                if($o->getTotal()){
                    $dollar=$o->getAvdollar();
                    $supprdollar+=$dollar;
                    foreach ($society as $k) {
                        $caissedollar=$k->getDollar();
                        $caissedollar-=$supprdollar;
                        $k->setDollar($caissedollar);
                    }
                }
                $em->remove($o);  
            }
            
            
            $em->flush();
            return $this->redirectToRoute('dashboard');
            $this->addFlash("success", "Operations supprimées!");
        }
        
        
        return $this->render('dashboard/facturationtemplot.html.twig', [
            'society'=>$society,
            'type'=>$type,
            'client'=>$user,
            'agent'=>$this->getUser()->getFullname(),
            'operation'=>$operations,
            'numero'=>$numero,
            'solde'=>$solde,
            'comis'=>$comis,
            'totalm'=>$totalm,
            'date'=>$date,
            'see'=>$request->get('see') ? $request->get('see') : false,
        ]);
    }

    /**
     * @Route("/updateoperationlotdubai/{id}", name="updateoperationlotdubai")
     */
    public function UpdateOperationLotDubai(Request $request, operationsRepository $operationsRepository,SocietyRepository $societyRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        $society=$societyRepository->findAll();
        $id=$operationsRepository->findOneBy(['id'=>$request->get('id')]);
        //$op=$operationsRepository->findOneBy(['id'=>$request->get('id')]);
        if (!$id) {
            $this->addFlash("error", "Attention cette operation n'existe pas!");
            return $this->redirectToRoute('dashboard');
        }
        $operation = new Operations();
        if ($id->getType() != "Lot") {
            $form = $this->createForm(OperationDubaiType::class, $operation);
            $form->handleRequest($request);
            $last = $id->getId() - 1 ;
            $getLast = $operationsRepository->findOneBy(['id'=> $last]);
            
        }else{
            $form = $this->createForm(OperationLotType::class, $operation);
            $form->handleRequest($request);
            $getLast = false;
        }
        if($getLast){
            $caisse = $getLast->getTotalm();
        }else{
            foreach ($society as $k) {
              $caisse = $k->getDollar();
            } 
        }
        
        
        if ($form->isSubmitted() && $form->isValid()) {
            if ($id->getType() == "Lot") {
                $id->setPoidair($form['poidair']->getData());
                $id->setAvdollar($form['avdollar']->getData());
                $id->settaux($form['taux']->getData());
                $id->setAvance($form['avance']->getData());
                
            }else{

                $id->setMontant($form['montant']->getData());
                $id->setTotalm($form['totalm']->getData());
                $id->setAvdollar($form['avdollar']->getData());
            
                
            }
            
            $id->setTime(date('H:i:s'));
            $id->setCreatedAt(new \DateTime(date('Y-m-d')));
            //dd($id);
            $em->flush();
            //dd($id);
            $this->addFlash("success", "Opération modifiée avec succès!"); 
            return $this->redirectToRoute('facturationtemplot', ['type'=>$id->getType(), 'numero'=>$id->getNumero()]);
            
        }
        if($request->get('delete')){
            $type = $id->getType();
            $em->remove($id);
            $em->flush();
            $this->addFlash("success", "Opération supprimée avec succès!");
            
            $referer = $request->headers->get('referer');
            if ($type == "Lot Dubai") return $this->redirect($referer);
            else return $this->redirectToRoute('dashboard');
        }
        
        return $this->render('dashboard/updateoperationlotdubai.html.twig', [
            'society'=>$society,
            'operation'=>$id,
            'caisse'=>$caisse,
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/validlot/{id}/{type}/{numero}", name="validlot")
     */
    public function validLot(Request $request, OperationsRepository $operationsRepository, UserRepository $userRepository, SocietyRepository $societyRepository): Response
    {
        $em= $this->getDoctrine()->getManager();
        $type=$request->get('type');
        $numero=$request->get('numero');
        $id=$request->get('id');
           
        $user=$userRepository->findOneBy(['id'=>$id]);
        $operations=$operationsRepository->findBy(
            ['type'=>$type,  'facture'=>'Ok', 'numero'=>$numero],
            ['id'=>'ASC']
        );
        if(!$operations){
            $user=false;
            $operations=$operationsRepository->findBy(
                ['tempclient'=>$id, 'type'=>$type,  'facture'=>'Ok', 'numero'=>$numero],
                ['id'=>'ASC']
            );
        }

        $montant=0;
        $tm=0;
        $fcfa=0;
        $avance=0;
        $solde=0;
        $comis=0;
        $mtotal=0;
        $reliquat=0;
        $society=$societyRepository->findAll();
        
        foreach ($society as $s) {
            if ($type=="Lot") { 
                $totalm=false;
                foreach ($operations as $o) {
                   $montant=$o->getTotal(); 
                   $tm+=$montant;
                   $avance= $o->getAvance();
                   $fcfa+=$avance;
                //    $o->setMontant($tm);
                //    $o->setTotalm($fcfa);
                   $o->setValid(true);
                   if($request->get('totalm')) {  
                       $o->setTotalm($request->get('totalm'));
                       $o->setPrixu($request->get('prixu'));
                       $o->setMontant($request->get('reliquat'));
                       $o->setQte($request->get('p24carat'));
                    }
                    $mtotal= $o->getTotalm();
                    $reliquat = $o->getMontant();
                    //dd($o->getTotalm());
                }
            }else{
                foreach ($society as $s) {
                    $dollar=$s->getDollar();
                    foreach ($operations as $o) {
                        $montant+=$o->getMontant();
                        $comis+=$o->getAvdollar();
                        $totalm=$o->getTotalm();
                        $solde=$montant;
                        $dollar=$totalm;
                        $o->setTotal($solde);
                        $o->setValid(true);
                    }
                    $s->setDollar($dollar);
                }
            }
        }
        if (!$request->get('see')) {
            if($request->get('totalm')) $em->flush();
            $this->addFlash("error", "Veuillez Compléter et valider la Facture.");
        }
        
        
        
        
        
        return $this->render('dashboard/validlot.html.twig', [
            'society'=>$society,
            'type'=>$type,
            'client'=>$user,
            'agent'=>$this->getUser()->getFullname(),
            'operations'=>$operations,
            'numero'=>$numero,
            'montant'=>$tm,
            'totalm'=>$totalm,
            'comis'=>$comis,
            'solde'=>$solde,
            'fcfa'=>$fcfa,
            'see'=>$request->get('see') ? $request->get('see') : false,
            'mtotal'=>$mtotal,
            'reliquat'=>$reliquat,
        ]);
    }

    


}
