<?php

namespace App\Services;

use App\Entity\MuscleGroup;
use App\Repository\MuscleGroupRepository;
use Symfony\Component\HttpFoundation\Response;

class MuscleGroupService
{
    private MuscleGroupRepository $muscleGroupRepository;

    public function __construct(MuscleGroupRepository $muscleGroupRepository)
    {
        $this->muscleGroupRepository = $muscleGroupRepository;

    }

    public function findMuscleGroupByType($type)
    {
        return $this->muscleGroupRepository->findOneBy(['type' => $type]);
    }

    public function saveMuscleGroup(MuscleGroup $muscleGroup): array
    {
        try {
            $group = $this->muscleGroupRepository->findOneBy(['name' => $muscleGroup->getType()]);
            if ($group) {
                throw new \Exception(message: 'Muscle group already exist');
            }
            $this->muscleGroupRepository->saveMuscleGroup($muscleGroup);

            return ['success' => true];
        } catch (\Exception $exception) {
            return ['error' => true, 'message' => $exception->getMessage()];
        }


    }

}

