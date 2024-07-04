<?php

namespace App\Repository;

use App\Entity\Exercise;
use App\Entity\MuscleGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Exercise>
 */
class ExerciseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exercise::class);
    }
    public function saveExercise(Exercise $exercise)
    {
        $this->getEntityManager()->persist($exercise);  //update sau save ->persist
        $this->getEntityManager()->flush();


    }
    public function deleteExercise(Exercise $exercise)
    {
        $this->getEntityManager()->remove($exercise);
        $this->getEntityManager()->flush();


    }
    public function findByMuscleGroup($muscleGroup)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.muscleGroup = :muscleGroup')
            ->setParameter('muscleGroup', $muscleGroup)
            ->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return Exercise[] Returns an array of Exercise objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Exercise
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
