<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Workout;
use App\Repository\WorkoutRepository;

class WorkoutService
{

    public function __construct(private WorkoutRepository $workoutRepository)
    {
    }

    public function saveWorkout(Workout $workout): void
    {
        $this->workoutRepository->saveWorkout($workout);
    }

    public function getAllWorkouts(): array
    {
        return $this->workoutRepository->findAll();
    }
}