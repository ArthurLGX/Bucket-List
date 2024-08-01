<?php

namespace App\Entity;

use App\Repository\BucketEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BucketEntityRepository::class)]
class BucketEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    //nom de taile max 255
    #[Assert\NotBlank(message: 'Le nom est obligatoire')]
    #[Assert\Length(min: 3, max: 255, minMessage: 'Le nom doit faire plus de 3 caractères', maxMessage: 'Le nom ne doit pas dépasser 255 caractères')]
    #[ORM\Column(type: 'string', length: 255)]
    //nom obligatoire

    private string $name;


    #[Assert\NotBlank(message: 'La description est obligatoire')]
    #[Assert\Length(min: 10, max: 255, minMessage: 'La description doit faire au moins 10 caractères', maxMessage: 'La description doit faire au maximum 255 caractères')]
    #[ORM\Column(type: 'string', length: 255)]
    private string $description;

    #[ORM\Column(type: 'boolean')]
    private bool $isPublished;

    #[ORM\Column(type: 'string', length: 6)]
    private string $status;


    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    #[ORM\ManyToOne(inversedBy: 'item')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CategoryEntity $category = null;

    #[ORM\ManyToOne(inversedBy: 'bucket')]
    private ?user $author = null;

    #[ORM\ManyToOne(inversedBy: 'bucketEntities')]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: AssociationBucketUser::class, mappedBy: 'bucket_id')]
    private Collection $associationBucketUsers;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }



    public function getIsPublished(): bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }


    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }


    public function __construct()
    {
        $this->status = 'active';
        $this->associationBucketUsers = new ArrayCollection();
    }

    public function isDone(): bool
    {
        return $this->status === 'done';
    }

    public function isUndone(): bool
    {
        return $this->status === 'undone';
    }

    public function getCategory(): ?CategoryEntity
    {
        return $this->category;
    }

    public function setCategory(?CategoryEntity $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getAuthor(): ?user
    {
        return $this->author;
    }

    public function setAuthor(?user $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, AssociationBucketUser>
     */
    public function getAssociationBucketUsers(): Collection
    {
        return $this->associationBucketUsers;
    }

    public function addAssociationBucketUser(AssociationBucketUser $associationBucketUser): static
    {
        if (!$this->associationBucketUsers->contains($associationBucketUser)) {
            $this->associationBucketUsers->add($associationBucketUser);
            $associationBucketUser->addBucketId($this);
        }

        return $this;
    }

    public function removeAssociationBucketUser(AssociationBucketUser $associationBucketUser): static
    {
        if ($this->associationBucketUsers->removeElement($associationBucketUser)) {
            $associationBucketUser->removeBucketId($this);
        }

        return $this;
    }

}
