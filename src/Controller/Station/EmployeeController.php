<?php

namespace App\Controller\Station;

use App\Entity\Fournisseur;
use App\Entity\User;
use App\Form\FournisseurType;
use App\Form\UserType;
use App\Form\UserUpdatePasswordType;
use App\Form\UserUpdateType;
use App\Repository\FournisseurRepository;
use App\Repository\SellCarburantRepository;
use App\Repository\SocietyRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/employee/management")
 */
class EmployeeController extends AbstractController
{
    private SocietyRepository $societyRepository;
    private UserRepository  $userRepository;
    private UserPasswordHasherInterface $passwordEncoder;

    public function __construct(SocietyRepository $societyRepository, UserRepository $userRepository, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->societyRepository = $societyRepository;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;

    }

    /**
     * @Route("/", name="app_station_employee_index", methods={"GET"})
     */
    public function index(): Response
    {
        $caisseStation = $this->societyRepository->findBy([],[],[1])[0]->getCaisseStation();
        if (in_array("ROLE_ADMIN", $this->getUser()->getRoles()) ||
            in_array("ROLE_SOUS_ADMIN", $this->getUser()->getRoles())
        ){
            $getAll = $this->userRepository->findBy(['type'=>'station-agent', 'type'=>'station-gerant']);
        }else {
            $getAll = $this->userRepository->findBy(['type'=>'station-agent']);
        }
        return $this->render('station-dashboard/employe_management/index.html.twig', [
            'agents' => $getAll,
            'caisseStation' =>number_format($caisseStation, 0, ',','.')??0,
        ]);
    }

    /**
     * @Route("/new", name="app_station_employee_new", methods={"GET", "POST"})
     */
    public function new(Request $request ): Response
    {
        $agent = new User();
        $form = $this->createForm(UserType::class, $agent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agent->setRoles(['ROLE_STATION_AGENT']);
            $agent->setType('station-agent');
            $password = $this->passwordEncoder->hashPassword($agent, $form->get('password')->getData());

            $agent->setPassword($password);
            $this->userRepository->add($agent);

            return $this->redirectToRoute('app_station_employee_index', [], Response::HTTP_SEE_OTHER);
        }

        $caisseStation = $this->societyRepository->findBy([],[],[1])[0]->getCaisseStation();

        return $this->renderForm('station-dashboard/employe_management/new.html.twig', [
            'agent' => $agent,
            'form' => $form,
            'caisseStation' =>number_format($caisseStation, 0, ',','.')??0,
        ]);
    }

    /**
     * @Route("/{id}", name="app_station_employee_show", methods={"GET"})
     */
    public function show(User $user, SellCarburantRepository $sellCarburantRepository): Response
    {
        $caisseStation = $this->societyRepository->findBy([],[],[1])[0]->getCaisseStation();

        return $this->render('station-dashboard/employe_management/show.html.twig', [
            'agent' => $user,
            'caisseStation' =>number_format($caisseStation, 0, ',','.')??0,
            'sellCarburant' => $sellCarburantRepository->findBy(['agent'=>$user->getFullname()]),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_station_employee_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserUpdateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->userRepository->add($user);
            return $this->redirectToRoute('app_station_employee_index', [], Response::HTTP_SEE_OTHER);
        }

        $caisseStation = $this->societyRepository->findBy([],[],[1])[0]->getCaisseStation();
        return $this->renderForm('station-dashboard/employe_management/edit.html.twig', [
            'agent' => $user,
            'form' => $form,
            'caisseStation' =>number_format($caisseStation, 0, ',','.')??0,
        ]);
    }

    /**
     * @Route("/{id}/edit/password", name="app_station_employee_edit_password", methods={"GET", "POST"})
     */
    public function editPassword(Request $request, User $user): Response
    {
        $form = $this->createForm(UserUpdatePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->passwordEncoder->hashPassword($user, $form->get('password')->getData());
            $user->setPassword($password);
            $this->userRepository->add($user);
            return $this->redirectToRoute('app_station_employee_index', [], Response::HTTP_SEE_OTHER);
        }

        $caisseStation = $this->societyRepository->findBy([],[],[1])[0]->getCaisseStation();
        return $this->renderForm('station-dashboard/employe_management/edit.html.twig', [
            'agent' => $user,
            'form' => $form,
            'caisseStation' =>number_format($caisseStation, 0, ',','.')??0,
        ]);
    }

    /**
     * @Route("/{id}", name="app_station_employee_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $this->userRepository->remove($user);
        }

        return $this->redirectToRoute('app_station_employee_index', [], Response::HTTP_SEE_OTHER);
    }
}
