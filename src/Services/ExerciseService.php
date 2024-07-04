<?php

namespace App\Services;

use App\Entity\Exercise;
use App\Entity\MuscleGroup;
use App\Repository\ExerciseRepository;
use App\Repository\MuscleGroupRepository;

class ExerciseService
{
    private ExerciseRepository $exerciseRepository;

    public function __construct(ExerciseRepository $exerciseRepository)
    {
        $this->exerciseRepository = $exerciseRepository;

    }

    public function saveExercise(Exercise $exercise): array
    {
        try {
            $group = $this->exerciseRepository->findOneBy(['name' => $exercise->getName()]);
            if ($group) {
                throw new \Exception('exercise already exist');
            }
            $this->exerciseRepository->saveExercise($exercise);

            return ['success' => true];
        } catch (\Exception $exception) {
            return ['error' => true, 'message' => $exception->getMessage()];
        }


    }

    public function getExerciseById($exerciseId): object
    {
       return $this->exerciseRepository->find($exerciseId);

    }
    public function deleteExercise($exercise): void
    {

        $this->exerciseRepository->deleteExercise($exercise);

    }
    public function getExercisesByMuscleGroup($muscleGroup)
    {
        return $this->exerciseRepository->findByMuscleGroup($muscleGroup);
    }



}

