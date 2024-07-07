<?php

namespace App\Controller;

use App\Entity\Workout;
use App\Repository\WorkoutRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExerciseLogController extends AbstractController
{
    private WorkoutRepository $workoutRepository;

    public function __construct(WorkoutRepository $workoutRepository)
    {
        $this->workoutRepository = $workoutRepository;
    }
    #[Route('/exercise/log', name: 'app_exercise_log')]
    public function index(): Response
    {
        return $this->render('exercise_log/index.html.twig', [
            'controller_name' => 'ExerciseLogController',
        ]);
    }

    #[Route('/exercise/log/{workoutId}', name: 'all_exercise_log', requirements: ['workoutId' => '\d+'])]
    public function show(int $workoutId): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not logged in');
        }


        $workout = $this->workoutRepository->findOneBy([
            'id' => $workoutId,
            'pers' => $user,
        ]);

        if (!$workout) {
            throw $this->createNotFoundException('Workout not found');
        }


        $exercises = $workout->getExerciseLogs();


        return $this->render('exercise_log/show.html.twig', [
            'workout' => $workout,
            'exercises' => $exercises,
        ]);
    }
}
