<?php
namespace App\Controller;

use App\Entity\Operations;
use App\Form\OperationTempType;
use App\Form\OperationtwotdrType;
use App\Form\OperationtwoType;
use App\Form\OperationType;
use App\Repository\OperationsRepository;
use App\Repository\SocietyRepository;
use App\Repository\UserRepository;
use App\Service\WhatsAppService;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use UltraMsg\WhatsAppApi;

/**
 * @Route("/operation")
 * @IsGranted("ROLE_USER")
 */
class OperationController extends AbstractController
{
    /**
     * @Route("/{id}/{type}/{transact}", name="operation")
     */
    public function Operations(Request $request, OperationsRepository $operationsRepository, UserRepository $userRepository, SocietyRepository $societyRepository): Response
    {
        $em= $this->getDoctrine()->getManager();
        $user=$userRepository->findOneBy(['id'=>$request->get('id'), 'type'=>$request->get('type')]);
        if (!$user) {
            $this->addFlash("error", "Attention ce client n'existe pas!");
            return $this->redirectToRoute('dashboard');
        }else{
            $transact=$request->get('transact');
        }
        
        $operation= new Operations();
       
        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);
        if($request->get('idop')){
            $operation = $operationsRepository->findOneBy(['id'=>$request->get('idop')]);
        }
        $formtwo= $this->createForm(OperationtwoType::class, $operation);
        $formtwo->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) { 
            $operation=$form->getData();
            $operation->setCreatedAt(new \DateTime(date('Y-m-d')));
            $operation->setTime(date('H:i:s'));
            $operation->setClient($user);
            $operation->setAgent($this->getUser()->getFullname());
            if ($this->getUser()->getAgency()) {
                $operation->setAgency($this->getUser()->getAgency());
            }
            $operation->setType($transact);
            $em->persist($operation);
            $em->flush(); 
            return new JsonResponse([ $operation->getClient()->getId(), $operation->getType(), $operation->getBase(),  date_format($operation->getCreatedAt(), 'Y-m-d 00:00:00')]);
        }
        
        
        if ($formtwo->isSubmitted() && $formtwo->isValid()) { 
            $operation=$formtwo->getData();
            $operation->setCreatedAt(new \DateTime(date('Y-m-d')));
            $operation->setTime(date('H:i:s'));
            $operation->setClient($user);
            if ($this->getUser()->getAgency()) {
                $operation->setAgency($this->getUser()->getAgency());
            }
            $operation->setAgent($this->getUser()->getFullname());
            $operation->setType($transact);
            $em->persist($operation);
            $em->flush();
            return new JsonResponse([ $operation->getClient()->getId(), $operation->getType(), $operation->getBase(),  date_format($operation->getCreatedAt(), 'Y-m-d 00:00:00')]);
        }
        

        $society=$societyRepository->findAll();
        return $this->render('dashboard/operations.html.twig', [
            'society'=>$society,
            'transact'=>$transact,
            'client'=>$user,
            'form'=>$form->createView(),
            'formtwo'=>$formtwo->createView(),
        ]);
    }

    /**
     * @Route("/updateoperation/{id}", name="updateoperation")
     */
    public function UpdateOperation(Request $request, OperationsRepository $operationsRepository, SocietyRepository $societyRepository): Response
    {
        $em= $this->getDoctrine()->getManager();
        $op=$operationsRepository->findOneBy(['id'=>$request->get('id')]);
        if (!$op) {
            $this->addFlash("error", "Attention cette operation n'existe pas!");
            return $this->redirectToRoute('dashboard');
        }
        $operation = new Operations();
        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $op->setBase(floatval($form['base']->getData()));
            if($form['avance']->getData() != "" && $form['avance']->getData() != null ){
                $op->setAvance($form['avance']->getData());
            }
            $op->setTime(date('H:i:s'));
            $op->setPoidair(floatval($form['poidair']->getData()));
            $op->setPoideau(floatval($form['poideau']->getData()));
            $op->setDensite(floatval($form['densite']->getData()));
            $op->setKarat(floatval($form['karat']->getData()));
            $op->setPrixu(floatval($form['prixu']->getData()));
            $op->setMontant(floatval($form['montant']->getData()));
            // $op->setQte(floatval($form['qte']->getData()));
            // $op->setTaux(floatval($form['taux']->getData()));
            // $op->setAvdollar(floatval($form['avdollar']->getData()));
        
            $em->flush();
            $this->addFlash("success", "Opération modifiée avec succès!");
            if (!$request->get('temp')) {
                return $this->redirectToRoute('dashboard');
            }else{
                $client= $op->getClient() ? $op->getClient()->getId() : $op->getTempclient();
                $type= $op->getType();
                $b=$op->getBase();
                $date=date_format($op->getCreatedAt(), 'Y-m-d 00:00:00');
                return $this->redirectToRoute('facturation', ['id'=>$client, 'type'=>$type, 'base'=>$b, 'date'=>$date ]);
            }
        }
        if($request->get('delete')){
            $em->remove($op);
            $em->flush();
            $this->addFlash("success", "Opération supprimée avec succès!");
            return $this->redirectToRoute('dashboard');
        }
        $society=$societyRepository->findAll();
        return $this->render('dashboard/updateoperation.html.twig', [
            'society'=>$society,
            'operation'=>$op,
            'form'=>$form->createView(),
        ]);
    }
    
    /**
     * @Route("/facturation/{id}/{type}/{base}/{date}", name="facturation")
     */
    public function Facturation(Request $request, WhatsAppService $whatsAppService, OperationsRepository $operationsRepository, UserRepository $userRepository, SocietyRepository $societyRepository): Response
    {
        $em= $this->getDoctrine()->getManager();
        $type=$request->get('type');
        $base=$request->get('base');
        $date=$request->get('date');
        $date=new \DateTime($date);
        $user=$userRepository->findOneBy(['id'=>$request->get('id')]);
        if (!$user) {
            $this->addFlash("error", "Attention ce client n'existe pas!");
            return $this->redirectToRoute('dashboard');
        }
        
        $operations=$operationsRepository->findBy(
            ['client'=>$user, 'type'=>$type, 'base'=>$base, 'createdAt'=>$date, 'facture'=>null],
            ['createdAt'=>'ASC'],
        );
        $recu=$operationsRepository->findOneBy(
            ['client'=>$user, 'type'=>$type, 'createdAt'=>$date, 'facture'=>null],
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
            if ($recu) {
                $numero=$recu->getNumero();
                $numero+=1;
            }
        }
        /* Calcule plus enregistrement */
        $totalair=0;
        $totaleau=0;
        $totaldensite=0;
        $totalcarat=0;
        $totalmontant=0;
        $total=0;
        $indollar=0;
        $avance=0;
        $society=$societyRepository->findAll();
        if($type == "Achat" || $type == "Vente"){
            $caisse=0;
            $solde=0;
            $barre=count($operations);
            
            if ($operations) {
                $solde=$user->getSolde();
                if ($this->getUser()->getAgency()) {
                    $agencycaisse=$this->getUser()->getAgency()->getCaisse();
                
                }else{
                    $agencycaisse=false;
                }
                foreach ($operations as $c){
                    $agent=$c->getAgent() ? : "AGENT";
                    $a=$c->getAvance();
                    $avance+=$a;
                    $totalair+=$c->getPoidair();
                    $totaleau+=$c->getPoideau();
                    $totaldensite+=$c->getDensite();
                    $totalcarat+=$c->getKarat();
                    $totalmontant+=$c->getMontant();
                    $total=$totalmontant - $avance;
                    $indollar=$c->getQte();
                    $c->setTotalm($totalmontant);
                    $c->setTotal($total);
                    $c->setNumero($numero);
                    $c->setFacture('Ok');
 
                }
                foreach ($society as $s) {
                    $caisse=$s->getCaisse();
                    if($type == "Achat"){
                        // $caisse=$caisse - $total;
                        $solde=$solde + $total;
                        // if ($agencycaisse) {
                        //     // $agencycaisse-=$total;
                        //     $this->getUser()->getAgency()->setCaisse($agencycaisse);
                        // }
                        // $s->setCaisse($caisse);
                        $user->setSolde($solde);
                        $body="$type Or "." Nº$numero, Total: $total. FCFA. 
                                Nouveau solde: $solde FCFA.  
                                Bien à vous ".$user->getFullname().". Ceci est un TEST de lapplication EASYGOLD";


                    }
                    elseif ($type == "Vente") {
                        $caisse=$caisse + $total;
                        $solde=$solde - $total;
                        if ($agencycaisse) {
                            $agencycaisse+=$total;
                            $this->getUser()->getAgency()->setCaisse($agencycaisse);
                        }
                        $s->setCaisse($caisse);
                        $user->setSolde($solde);
                        $body="$type Or "." Nº$numero, Total: $total. FCFA. 
                                Nouveau solde: $solde FCFA.  
                                Bien à vous ".$user->getFullname().". Ceci est un TEST de lapplication EASYGOLD";

                    }   
                }
            }
            if ($request->get('valid') == true) {
                $em->flush();

                $whatsAppService->sendMessage($user->getTel(), $body);
                return new JsonResponse($request->get('valid'));
                $this->addFlash("success", "Facture enregistrée!");
            }
        }
        elseif($type == "Dépôt" || $type == "Rétrait" ){
            $caisse=0;
            $solde=0;
            $barre=false;
            if ($this->getUser()->getAgency()) {
                $agencycaisse=$this->getUser()->getAgency()->getCaisse();
            }else{
                $agencycaisse=false;
            }
            
            if($recu){
                $agent=$recu->getAgent() ? : "AGENT";
                $montant=$recu->getMontant();
                $solde=$user->getSolde(); 
                $recu->setFacture('Ok');
                $recu->setBase(0);
                foreach ($society as $s) {
                $caisse=$s->getCaisse();
                    if($type=="Dépôt"){
                        
                        $solde=$solde + $montant;
                        if ($agencycaisse) {
                            $agencycaisse+=$montant;
                            $caisse=$caisse + $montant;
                        }
                        else{
                            $caisse=$caisse + $montant;
                        }
                        
                    }elseif ($type=="Rétrait") {
                        
                        $solde=$solde - $montant; 
                        if ($agencycaisse) {
                            $agencycaisse-=$montant;
                            $caisse=$caisse - $montant;
                        }
                        else{
                            $caisse=$caisse - $montant;
                        }     
                    }
                    $s->setCaisse($caisse);
                    $user->setSolde($solde);
                    $recu->setNumero($numero);
                    $recu->setTime(date('H:i:s'));
                    if ($agencycaisse) {
                        $this->getUser()->getAgency()->setCaisse($agencycaisse);
                    }
                    
                    $em->flush();
                    $body=" Reçu de votre $type"." Nº$numero de $montant FCFA.  
                            Motif:". $recu->getMotif(). "
                            Solde actuel: $solde. Bien à vous ".$user->getFullname().". Ceci est un TEST de lapplication EASYGOLD";

                    $whatsAppService->sendMessage($user->getTel(), $body);

                    $this->addFlash("success", "Facture enregistrée!");
                }
     
            }
        }
        
        return $this->render('dashboard/facturation.html.twig', [
            'society'=>$society,
            'type'=>$type,
            'client'=>$user,
            'operations'=>$operations,
            'date'=>$date,
            'numero'=>$numero,
            'totalair'=>$totalair,
            'totaleau'=>$totaleau,
            'totaldensite'=>$totaldensite,
            'totalcarat'=>$totalcarat,
            'totalmontant'=>$totalmontant,
            'total'=>$total,
            'agent'=>$agent,
            'barre'=>$barre,
            'recu'=>$recu,
            'qte'=>$indollar,
        ]);
    }

    /**
     * @Route("/showfacture/{id}/{type}/{base}/{date}", name="showfacture")
     */
    public function ShowFacturation(Request $request, OperationsRepository $operationsRepository, UserRepository $userRepository, SocietyRepository $societyRepository): Response
    {
        $em= $this->getDoctrine()->getManager();
        $type=$request->get('type');
        $base=$request->get('base');
        $date=$request->get('date');
        $date=new \DateTime($date);
        $user=$userRepository->findOneBy(['id'=>$request->get('id')]);
        if (!$user) {
            $this->addFlash("error", "Attention ce client n'existe pas!");
            return $this->redirectToRoute('dashboard');
        }
        
        $operations=$operationsRepository->findBy(
            ['client'=>$user, 'type'=>$type, 'base'=>$base, 'createdAt'=>$date, 'facture'=>'Ok'],
            ['createdAt'=>'ASC'],
        );
        $recu=$operationsRepository->findOneBy(
            ['client'=>$user, 'type'=>$type, 'createdAt'=>$date, 'facture'=>'Ok', 'numero'=>$request->get('numero')],
        );
        
        /* Calcule plus enregistrement */
        $totalair=0;
        $totaleau=0;
        $totaldensite=0;
        $totalcarat=0;
        $totalmontant=0;
        $total=0;
        $indollar=0;
        $barre=count($operations);
        foreach ($operations as $c) {
            $agent=$c->getAgent();
            $totalair+=$c->getPoidair();
            $totaleau+=$c->getPoideau();
            $totaldensite+=$c->getDensite();
            $totalcarat+=$c->getKarat();
            $totalmontant+=$c->getMontant();
            $total=$totalmontant - $c->getAvance();
            $indollar=$c->getQte();
        }
        
        // $this->addFlash("success", "Facture enregistrée!");
        
        $society=$societyRepository->findAll();
        return $this->render('dashboard/showfacture.html.twig', [
            'society'=>$society,
            'type'=>$type,
            'client'=>$user,
            'operations'=>$operations,
            'date'=>$date,
            'total'=>$total,
            'totalmontant'=>$totalmontant,
            'totalair'=>$totalair,
            'totaleau'=>$totaleau,
            'totaldensite'=>$totaldensite,
            'totalcarat'=>$totalcarat,
            'numero'=>$request->get('numero'), 
            'agent'=>$agent,
            'barre'=>$barre,
            'recu'=>$recu,
            'qte'=>$indollar,
        ]);
    }

    /**
     * @Route("/temp/{transact}", name="operationtemp")
     */
    public function OperationsTemp(Request $request, SocietyRepository $societyRepository): Response
    {
        $em= $this->getDoctrine()->getManager();
        $transact=$request->get('transact');
        
        $operation = new Operations();
        $form = $this->createForm(OperationTempType::class, $operation);
        $form->handleRequest($request);
        
       
            if ($form->isSubmitted() && $form->isValid()) { 
                $operation=$form->getData();
                $operation->setCreatedAt(new \DateTime(date('Y-m-d')));
                $operation->setTime(date('H:i:s'));
                $operation->setAgent($this->getUser()->getFullname());
                if ($this->getUser()->getAgency()) {
                    $operation->setAgency($this->getUser()->getAgency());
                }
                $operation->setType($transact);
                $em->persist($operation);
                $em->flush();
                return new JsonResponse([ $operation->getTempclient(), $operation->getType(), $operation->getBase(),  date_format($operation->getCreatedAt(), 'Y-m-d 00:00:00'), $operation->getTel() ]);
            }
            
        

        $society=$societyRepository->findAll();
        return $this->render('dashboard/operationstemp.html.twig', [
            'society'=>$society,
            'transact'=>$transact,
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/updateoperationtemp/{id}", name="updateoperationtemp")
     */
    public function UpdateOperationTemp(Request $request, OperationsRepository $operationsRepository, SocietyRepository $societyRepository): Response
    {
        $em= $this->getDoctrine()->getManager();
        $op=$operationsRepository->findOneBy(['id'=>$request->get('id')]);
        if (!$op) {
            $this->addFlash("error", "Attention cette operation n'existe pas!");
            return $this->redirectToRoute('dashboard');
        }
        $operation = new Operations();
        $form = $this->createForm(OperationTempType::class, $operation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $op->setTempclient($form['tempclient']->getData());
            $op->setTel($form['tel']->getData());
            $op->setBase(floatval($form['base']->getData()));
           
            if($form['avance']->getData() != "" && $form['avance']->getData() != null ){
                $op->setAvance($form['avance']->getData());
            }
            $op->setTime(date('H:i:s'));
            $op->setPoidair(floatval($form['poidair']->getData()));
            $op->setPoideau(floatval($form['poideau']->getData()));
            $op->setDensite(floatval($form['densite']->getData()));
            $op->setKarat(floatval($form['karat']->getData()));
            $op->setPrixu(floatval($form['prixu']->getData()));
            $op->setMontant(floatval($form['montant']->getData()));
            // $op->setQte(floatval($form['qte']->getData()));
            // $op->setTaux(floatval($form['taux']->getData()));
            // $op->setAvdollar(floatval($form['avdollar']->getData()));
        
            $em->flush();
            $this->addFlash("success", "Opération modifiée avec succès!");
            if (!$request->get('temp')) {
                return $this->redirectToRoute('dashboard');
            }else{
                $client= $op->getClient() ? $op->getClient()->getId() : $op->getTempclient();
                $type= $op->getType();
                $b=$op->getBase();
                $date=date_format($op->getCreatedAt(), 'Y-m-d 00:00:00');
                return $this->redirectToRoute('facturationtemp', ['id'=>$client, 'type'=>$type, 'base'=>$b, 'date'=>$date ]);
            }
        }
        if($request->get('delete')){
            $em->remove($op);
            $em->flush();
            $this->addFlash("success", "Opération supprimée avec succès!");
            return $this->redirectToRoute('dashboard');
        }
        $society=$societyRepository->findAll();
        return $this->render('dashboard/updateoperationtemp.html.twig', [
            'society'=>$society,
            'operation'=>$op,
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/facturationtemp/{id}/{type}/{base}/{date}", name="facturationtemp")
     */
    public function FacturationTemp(Request $request, OperationsRepository $operationsRepository, UserRepository $userRepository, SocietyRepository $societyRepository): Response
    {
        $em= $this->getDoctrine()->getManager();
        $type=$request->get('type');
        $base=$request->get('base');
        $date=$request->get('date');
        $id=$request->get('id');
        $user=$operationsRepository->findOneBy(
            ['tempclient'=>$id],
        );
        $date=new \DateTime($date);
        
        
        $operations=$operationsRepository->findBy(
            ['tempclient'=>$id, 'type'=>$type, 'base'=>$base, 'createdAt'=>$date, 'facture'=>null],
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
        $totalair=0;
        $totaleau=0;
        $totaldensite=0;
        $totalcarat=0;
        $totalmontant=0;
        $total=0;
        $indollar=0;
        $avance=0;
        $society=$societyRepository->findAll();
        if($type == "Achat" || $type == "Vente"){
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
                    $a=$c->getAvance();
                    $avance+=$a;
                    $totalair+=$c->getPoidair();
                    $totaleau+=$c->getPoideau();
                    $totaldensite+=$c->getDensite();
                    $totalcarat+=$c->getKarat();
                    $totalmontant+=$c->getMontant();
                    $total=$totalmontant - $avance;
                    $indollar=$c->getQte();
                    $c->setTotalm($totalmontant);
                    $c->setTotal($total);
                    $c->setNumero($numero);
                    $c->setFacture('Ok');
                   
                   
                }
                foreach ($society as $s) {
                        
                    $caisse=$s->getCaisse();
                    if($type == "Achat"){
                        
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
                    }
                    elseif ($type == "Vente") {
                        
                        if ($agencycaisse) {
                            $agencycaisse+=$total;
                            $caisse=$caisse + $total;
                            $this->getUser()->getAgency()->setCaisse($agencycaisse);
                            $s->setCaisse($caisse);
                        }else{
                            $caisse=$caisse + $total;
                            $s->setCaisse($caisse);
                        }
                        
                    }   
                }
            }
        }
        if ($request->get('valid') == true) {
            $em->flush();
            return new JsonResponse($request->get('valid'));
            $this->addFlash("success", "Facture enregistrée!");
        }
        
        
        return $this->render('dashboard/facturationtemp.html.twig', [
            'society'=>$society,
            'type'=>$type,
            'client'=>$user,
            'agent'=>$this->getUser()->getFullname(),
            'operations'=>$operations,
            'date'=>$date,
            'numero'=>$numero,
            'totalair'=>$totalair,
            'totaleau'=>$totaleau,
            'totaldensite'=>$totaldensite,
            'totalcarat'=>$totalcarat,
            'totalmontant'=>$totalmontant,
            'total'=>$total,
            // 'agent'=>$agent,
            'barre'=>$barre,
            'qte'=>$indollar,
        ]);
    }

    /**
     * @Route("/showfacturetemp/{id}/{type}/{base}/{date}", name="showfacturetemp")
     */
    public function ShowFacturationTemp(Request $request, OperationsRepository $operationsRepository, UserRepository $userRepository, SocietyRepository $societyRepository): Response
    {
        $em= $this->getDoctrine()->getManager();
        $type=$request->get('type');
        $base=$request->get('base');
        $date=$request->get('date');
        $id=$request->get('id');
        $date=new \DateTime($date);
        $user=$operationsRepository->findOneBy(
            ['tempclient'=>$id],
        );
        
        $operations=$operationsRepository->findBy(
            ['tempclient'=>$id, 'type'=>$type, 'base'=>$base, 'createdAt'=>$date, 'facture'=>'Ok'],
            ['createdAt'=>'DESC'],
        );
        if ($type == "Dépôt" || $type == "Rétrait") {
            $operations=$operationsRepository->findOneBy(
                ['tempclient'=>$id, 'type'=>$type, 'base'=>$base, 'createdAt'=>$date, 'facture'=>'Ok'],
                ['createdAt'=>'DESC'],
            ); 
            $barre = false; 
        }else{
            $barre=count($operations);
        }

        
        /* Calcule plus enregistrement */
        $totalair=0;
        $totaleau=0;
        $totaldensite=0;
        $totalcarat=0;
        $totalmontant=0;
        $total=0;
        $totalqte=0;
        $totalprixu=0;
        $montant=0;
        $indollar=0;
        
        
        foreach ($operations as $c) {
            // $agent=$c->getAgent();
            $totalair+=$c->getPoidair();
            $totaleau+=$c->getPoideau();
            $totaldensite+=$c->getDensite();
            $totalcarat+=$c->getKarat();
            $totalmontant+=$c->getMontant();
            $total=$totalmontant - $c->getAvance();
            $indollar=$c->getQte();
            if($type == "Achat de produit" || $type == "Vente de produit"){
                $totalmontant+=$c->getTotalm();
                $totalqte+=$c->getQte();
                $totalprixu+=$c->getPrixu();
                $total=$totalmontant;
            }
            if ($type == "DF" || $type == "EF" || $type == "FD" || $type == "FE") {
                $totalmontant=$c->getTotalm();
                $montant+=$c->getMontant();
                $total=$totalmontant;
            }
           
        }
        
        // $this->addFlash("success", "Facture enregistrée!");
        
        $society=$societyRepository->findAll();
        return $this->render('dashboard/showfacturetemp.html.twig', [
            'society'=>$society,
            'type'=>$type,
            'client'=>$user,
            'operations'=>$operations,
            'date'=>$date,
            'total'=>$total,
            'totalmontant'=>$totalmontant,
            'totalair'=>$totalair,
            'totaleau'=>$totaleau,
            'totalqte'=>$totalqte,
            'totalprixu'=>$totalprixu,
            'totaldensite'=>$totaldensite,
            'totalcarat'=>$totalcarat,
            'montant'=>$montant,
            'numero'=>$request->get('numero'), 
            // 'agent'=>$agent,
            'barre'=>$barre,
            'qte'=>$indollar,
        ]);
    }
    
    /**
     * @Route("/getlast/{id}", name="getlastops")
     */
    public function getLastOps(Request $request, OperationsRepository $operationsRepository): Response
    {
       
        
        if($request->get('prev') == 1){
            $getlasts=$operationsRepository->getLastOps($request->get('id'));
        }
        if ($request->get('idop')) {
            $prevId=$request->get('idop');
            $prevId= $prevId - 1; 
            $getlasts=$operationsRepository->getBackLastOps($prevId);
        }

        if ($getlasts){
            foreach ($getlasts as $k) {
                return new JsonResponse($k);
            } 
        }
    }
    /**
     * @Route("/getnext/{id}", name="getnextops")
     */
    public function getNextOps(Request $request, OperationsRepository $operationsRepository): Response
    {
        if ($request->get('idop')) {
            $prevId=$request->get('idop');
            $prevId= $prevId + 1; 
            $getlasts=$operationsRepository->getBackLastOps($prevId);
        }

        if ($getlasts){
            foreach ($getlasts as $k) {
                return new JsonResponse($k);
            } 
        }
    }
    /**
     * @Route("/updateops/{id}", name="updateops")
     */
    public function updateOps(Request $request): Response    
    {
        
        $data=$request->request->all();
        $operation=$this->getDoctrine()->getRepository(Operations::class)->findOneBy(['id'=>$data['id']]);
        
        if (!$operation->getTempclient()){
            $operation->setBase($data['operation']['base']);
            $operation->setPoidair($data['operation']['poidair']);
            $operation->setPoideau($data['operation']['poideau']);
            $operation->setDensite($data['operation']['densite']);
            $operation->setKarat($data['operation']['karat']);
            $operation->setPrixu($data['operation']['prixu']);
            $operation->setMontant($data['operation']['montant']);
            $operation->setCreatedAt(new \DateTime(date('Y-m-d')));
            $operation->setTime(date('H:i:s'));
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Opération modifiée avec succès.');
            return new JsonResponse([ $operation->getClient()->getId(), $operation->getType(), $operation->getBase(),  date_format($operation->getCreatedAt(), 'Y-m-d 00:00:00'), $operation->getId()]);
        }else{ 
            $operation->setBase($data['operation_temp']['base']);
            $operation->setPoidair($data['operation_temp']['poidair']);
            $operation->setPoideau($data['operation_temp']['poideau']);
            $operation->setDensite($data['operation_temp']['densite']);
            $operation->setKarat($data['operation_temp']['karat']);
            $operation->setPrixu($data['operation_temp']['prixu']);
            $operation->setMontant($data['operation_temp']['montant']);
            $operation->setCreatedAt(new \DateTime(date('Y-m-d')));
            $operation->setTime(date('H:i:s'));
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Opération modifiée avec succès.');
            return new JsonResponse([ $operation->getTempclient(), $operation->getType(), $operation->getBase(),  date_format($operation->getCreatedAt(), 'Y-m-d 00:00:00'), $operation->getTel(), $operation->getId()]);
        }

      
    }

     /**
     * @Route("/{transact}", name="operationtdr")
     */
    public function OperationsTempDR(Request $request, UserRepository $userRepository, SocietyRepository $societyRepository): Response
    {
        $em= $this->getDoctrine()->getManager();        
        $transact=$request->get('transact');
        $operation= new Operations();
        $formtwo= $this->createForm(OperationtwotdrType::class, $operation);
        $formtwo->handleRequest($request);
            if ($formtwo->isSubmitted() && $formtwo->isValid()) { 
                $operation=$formtwo->getData();
                $operation->setCreatedAt(new \DateTime(date('Y-m-d')));
                $operation->setTime(date('H:i:s'));
                $operation->setType($transact);
                $em->persist($operation);
                $em->flush();
                return new JsonResponse([ $operation->getType(),  date_format($operation->getCreatedAt(), 'Y-m-d 00:00:00'), $operation->getTempclient()]);
            }
        

        $society=$societyRepository->findAll();
        return $this->render('dashboard/operationstempdr.html.twig', [
            'society'=>$society,
            'transact'=>$transact,
            // 'client'=>$user,
            // 'form'=>$form->createView(),
            'formtwo'=>$formtwo->createView(),
        ]);
    }

    /**
     * @Route("/facturationtdr/{id}/{type}/{date}", name="facturationtdr")
     */
    public function FacturationTempDR(Request $request, OperationsRepository $operationsRepository, UserRepository $userRepository, SocietyRepository $societyRepository): Response
    {
        $em= $this->getDoctrine()->getManager();
        $type=$request->get('type');
        $id=$request->get('id');
        $date=$request->get('date');
        $date=new \DateTime($date);
        $recu=$operationsRepository->findOneBy(
            ['tempclient'=>$id, 'type'=>$type, 'createdAt'=>$date, 'facture'=>null],
        );
        
        $setnumero=$operationsRepository->setNumero($type);
        
        /* Attribution de numero de facture ou reçu */
        if($setnumero){
            foreach ($setnumero as $k) {
                $numero=$k["numero"];
                $numero+=1;
            }
        }else{
            if ($recu) {
                $numero=$recu->getNumero();
                $numero+=1;
            }
        }
        /* Calcule plus enregistrement */
        $totalair=0;
        $totaleau=0;
        $totaldensite=0;
        $totalcarat=0;
        $totalmontant=0;
        $total=0;
       
        $society=$societyRepository->findAll();
        
        if($type == "Dépôt" || $type == "Rétrait" ){
            $caisse=0;
            $barre=false;
            if ($this->getUser()->getAgency()) {
                $agencycaisse=$this->getUser()->getAgency()->getCaisse();
            
            }else{
                $agencycaisse=false;
            }
            if($recu){
                $agent=$recu->getAgent();
                $montant=$recu->getMontant();
                $recu->setFacture('Ok');
                $recu->setBase(0);
                foreach ($society as $s) {
                $caisse=$s->getCaisse();
                    if($type=="Dépôt"){ 
                        if ($agencycaisse) {
                            $agencycaisse+=$montant;
                            $caisse=$caisse + $montant;
                            $this->getUser()->getAgency()->setCaisse($agencycaisse);
                        }else $caisse=$caisse + $montant;
                    }elseif ($type=="Rétrait") {
                        if ($agencycaisse) {
                            $agencycaisse-=$montant;
                            $caisse=$caisse - $montant;
                            $this->getUser()->getAgency()->setCaisse($agencycaisse);
                        }else $caisse=$caisse - $montant; 
                    }
                    $s->setCaisse($caisse);
                    $recu->setNumero($numero);
                    $recu->setTime(date('H:i:s'));
                    
                    
                    $em->flush();
                }
     
            }
        }
        $this->addFlash("success", "Facture enregistrée!");
        
        return $this->render('dashboard/facturationtempdr.html.twig', [
            'society'=>$society,
            'type'=>$type,
            'date'=>$date,
            'numero'=>$numero,
            'totalair'=>$totalair,
            'totaleau'=>$totaleau,
            'totaldensite'=>$totaldensite,
            'totalcarat'=>$totalcarat,
            'totalmontant'=>$totalmontant,
            'total'=>$total,
            'agent'=>$agent,
            'barre'=>$barre,
            'recu'=>$recu,
        ]);
    }
}
