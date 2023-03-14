<?php

namespace App\Controller;

use App\Entity\Society;
use App\Entity\User;
use App\Form\SocietyType;
use App\Form\UserType;
use App\Repository\SocietyRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request, SocietyRepository $societyRepository, FileUploader $fileUploader): Response
    {
        $Society= new Society();
        $form = $this->createForm(SocietyType::class, $Society);
        
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $Society=$form->getData();
            $logo=$form['logo']->getData();
            if ($logo) {
                $logo=$fileUploader->upload($logo);
                $Society->setLogo($logo);
            }
            $em->persist($Society);
            $em->flush();
            $this->addFlash('success', 'Votre Société a été ajoutée avec succès!');
            return $this->redirectToRoute('createadmin');
        }

        $check=$societyRepository->findAll();
        if ($check) {
            return $this->redirectToRoute('createadmin');
        }
        
        return $this->render('base/index.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/createadmin", name="createadmin")
     */
    public function CreateAdmin(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordEncoder,SocietyRepository $societyRepository): Response
    {
        $getAdmin=$userRepository->findAll();
        
        $check=$societyRepository->findAll();
        if (!$check){
            return $this->redirectToRoute('index');
        }
        if($getAdmin){
            return $this->redirectToRoute('app_login');
        }

        $em=$this->getDoctrine()->getManager();
        
        $user= new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $user=$form->getData();
            $password = $passwordEncoder->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_ADMIN']);
            $user->setType('Super');
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Votre compte a été créé avec succès!');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('base/createadmin.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
