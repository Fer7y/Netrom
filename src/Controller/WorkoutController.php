<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Workout;
use App\Form\WorkoutType;
use App\Services\WorkoutService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WorkoutController extends AbstractController
{
    #[Route('/workouts', name: 'app_workout')]
    public function index(WorkoutService $workoutService): Response
    {
        return $this->render('workout/index.html.twig', [
            'workouts' => $workoutService->getAllWorkouts(),
        ]);
    }

    #[Route('/workout/create', name: 'add_workout')]
    public function store(Request $request, WorkoutService $workoutService): Response
    {
        $workout = new Workout();

        $form = $this->createForm(WorkoutType::class, $workout);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Workout $workout */
            $workout = $form->getData();

            /** @var User $user */
            $user = $this->getUser();
            if ($user) {
                $workout->setPers($user);
            }

            $workoutService->saveWorkout($workout);

            return $this->redirectToRoute('app_workout');
        }

        return $this->render('workout/store.html.twig', [
            'form' => $form,
        ]);
    }
}