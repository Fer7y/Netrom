<?php

namespace App\Controller;

use App\Entity\MuscleGroup;
use App\Entity\User;
use App\Form\MuscleGroupType;
use App\Form\UserType;
use App\Repository\MuscleGroupRepository;
use App\Repository\UserRepository;
use App\Services\MuscleGroupService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class MuscleGroupController extends AbstractController
{
    #[Route('/muscle-group', name: 'app_muscle_group')]
    public function index(): Response
    {
        return $this->render('muscle_group/index.html.twig', [
            'controller_name' => 'MusleGroupController',
        ]);
    }

    #[Route('/muscle-group/add', name: 'add_muscle_group')]
    public function add(Request $request, MuscleGroupService $muscleGroupService): Response
    {
        $muscleGroup = new MuscleGroup();

        $form = $this->createForm(MuscleGroupType::class, $muscleGroup);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $muscleGroup = $form->getData();

            $status = $muscleGroupService->saveMuscleGroup($muscleGroup);

            if ($status['error']) {
                $this->addFlash('error', $status['message']);
                return $this->redirectToRoute('add_muscle_group');
            }
            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('app_muscle_group');
        }

        return $this->render('muscle_group/add.html.twig', [
            'form' => $form,
        ]);

    }

    #[Route('/muscle-group/all', name: 'get_all_muscle_groups')]
    public function get(MuscleGroupRepository $entityManager): Response
    {
        $muscleGroups = $entityManager->findAll();


        return $this->render('muscle_group/get.html.twig', ["musclegroups" => $muscleGroups]);
    }

}

