<?php

namespace App\Entity;

use App\Repository\AssociationBucketUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssociationBucketUserRepository::class)]
class AssociationBucketUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'associationBucketUsers')]
    private Collection $userId;

    #[ORM\ManyToMany(targetEntity: BucketEntity::class, inversedBy: 'associationBucketUsers')]
    private Collection $bucket_id;



    public function __construct()
    {
        $this->userId = new ArrayCollection();
        $this->bucket_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserId(): Collection
    {
        return $this->userId;
    }

    public function addUserId(User $userId): static
    {
        if (!$this->userId->contains($userId)) {
            $this->userId->add($userId);
        }

        return $this;
    }

    public function removeUserId(User $userId): static
    {
        $this->userId->removeElement($userId);

        return $this;
    }

    /**
     * @return Collection<int, BucketEntity>
     */
    public function getBucketId(): Collection
    {
        return $this->bucket_id;
    }

    public function addBucketId(BucketEntity $bucketId): static
    {
        if (!$this->bucket_id->contains($bucketId)) {
            $this->bucket_id->add($bucketId);
        }

        return $this;
    }

    public function removeBucketId(BucketEntity $bucketId): static
    {
        $this->bucket_id->removeElement($bucketId);

        return $this;
    }
}
