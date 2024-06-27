<?php

namespace App\Entity;

use App\Repository\ExerciseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExerciseRepository::class)]
class Exercise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, MuscleGroup>
     */
    #[ORM\ManyToMany(targetEntity: MuscleGroup::class, inversedBy: 'exercises')]
    private Collection $muscleid;

    public function __construct()
    {
        $this->muscleid = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, MuscleGroup>
     */
    public function getMuscleid(): Collection
    {
        return $this->muscleid;
    }

    public function addMuscleid(MuscleGroup $muscleid): static
    {
        if (!$this->muscleid->contains($muscleid)) {
            $this->muscleid->add($muscleid);
        }

        return $this;
    }

    public function removeMuscleid(MuscleGroup $muscleid): static
    {
        $this->muscleid->removeElement($muscleid);

        return $this;
    }


}
