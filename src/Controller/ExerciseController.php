<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Entity\MuscleGroup;
use App\Form\ExerciseType;
use App\Form\MuscleGroupType;
use App\Repository\ExerciseRepository;
use App\Repository\MuscleGroupRepository;
use App\Services\ExerciseService;
use App\Services\MuscleGroupService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExerciseController extends AbstractController
{
    #[Route('/exercise', name: 'app_exercise')]
    public function index(): Response
    {
        return $this->render('exercise/index.html.twig', [
            'controller_name' => 'ExerciseController',
        ]);
    }


    #[Route('/exercise/add', name: 'add_exercise')]
    public function add(Request $request, ExerciseService $exerciseService): Response
    {
        $exercise = new Exercise();

        $form = $this->createForm(ExerciseType::class, $exercise);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $exercise = $form->getData();

            $status = $exerciseService->saveExercise($exercise);

            if (isset($status['error']) && $status['error']) {
                $this->addFlash('error', $status['message']);
                return $this->redirectToRoute('add_exercise');
            }
            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('app_exercise');
        }

        return $this->render('exercise/add.html.twig', [
            'form' => $form,
        ]);

    }

    #[Route('/exercise/all', name: 'get_all_exercises')]
    public function get(ExerciseRepository $entityManager): Response
    {
        $exercises = $entityManager->findAll();


        return $this->render('exercise/get.html.twig', ["exercises" => $exercises]);
    }

    #[Route('/exercise/{id}/update', name: 'edit_exercise')]
    public function edit(Request $request, ExerciseService $exerciseService, $id)
    {
        $form = $this->createForm(ExerciseType::class, $exerciseService->getExerciseById($id));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $exercise = $form->getData();

            $status = $exerciseService->saveExercise($exercise);

            if (isset($status['error']) && $status['error']) {
                $this->addFlash('error', $status['message']);
                return $this->redirectToRoute('add_exercise');
            }
            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('app_exercise');
        }

        return $this->render('exercise/add.html.twig', [
            'form' => $form,
        ]);

    }

    #[Route('/exercise/{id}/delete', name: 'delete_exercise', methods: ['DELETE'])]
    public function delete(Request $request, ExerciseService $exerciseService, $id)
    {
        $exercise = $exerciseService->getExerciseById($id);
        $exerciseService->deleteExercise($exercise);

        return $this->redirectToRoute('get_all_exercises');

    }

    #[Route('/exercise/musclegroupexercises', name: 'show_exercises', methods: ['GET'])]
    public function getExercisesByMuscleGroup(Request $request, ExerciseService $exerciseService, MuscleGroupService $muscleGroupService): Response
    {
        $muscleGroup = $muscleGroupService->findMuscleGroupByType($request->query->get('muscleGroup'));
        $exercises = $exerciseService->getExercisesByMuscleGroup($muscleGroup);

        if (!count($exercises)) {
            return $this->render('exercise/no_exercises.html.twig', [
                'muscleGroup' => $muscleGroup,
            ]);
        }

        return $this->render('exercise/exercises_by_muscle_group.html.twig', [
            'muscleGroup' => $muscleGroup,
            'exercises' => $exercises,
        ]);
    }
}
