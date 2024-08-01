<?php

namespace App\Entity;

use App\Repository\CategoryEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryEntityRepository::class)]
class CategoryEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\OneToMany(targetEntity: BucketEntity::class, mappedBy: 'category')]
    private Collection $item;

    public function __construct()
    {
        $this->item = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, BucketEntity>
     */
    public function getItem(): Collection
    {
        return $this->item;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addItem(BucketEntity $item): static
    {
        if (!$this->item->contains($item)) {
            $this->item->add($item);
            $item->setCategory($this);
        }

        return $this;
    }

    public function removeItem(BucketEntity $item): static
    {
        if ($this->item->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getCategory() === $this) {
                $item->setCategory(null);
            }
        }

        return $this;
    }
}
