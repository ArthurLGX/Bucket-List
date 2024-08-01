<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $lastname = null;

    #[ORM\OneToMany(targetEntity: BucketEntity::class, mappedBy: 'user')]
    private Collection $bucketEntities;

    #[ORM\ManyToMany(targetEntity: AssociationBucketUser::class, mappedBy: 'userId')]
    private Collection $associationBucketUsers;



    public function __construct()
    {
        $this->bucket = new ArrayCollection();
        $this->bucketEntities = new ArrayCollection();
        $this->associationBucketUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return Collection<int, BucketEntity>
     */
    public function getBucket(): Collection
    {
        return $this->bucket;
    }

    /**
     * @return Collection<int, BucketEntity>
     */
    public function getBucketEntities(): Collection
    {
        return $this->bucketEntities;
    }

    public function addBucketEntity(BucketEntity $bucketEntity): static
    {
        if (!$this->bucketEntities->contains($bucketEntity)) {
            $this->bucketEntities->add($bucketEntity);
            $bucketEntity->setUser($this);
        }

        return $this;
    }

    public function removeBucketEntity(BucketEntity $bucketEntity): static
    {
        if ($this->bucketEntities->removeElement($bucketEntity)) {
            // set the owning side to null (unless already changed)
            if ($bucketEntity->getUser() === $this) {
                $bucketEntity->setUser(null);
            }
        }

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
            $associationBucketUser->addUserId($this);
        }

        return $this;
    }

    public function removeAssociationBucketUser(AssociationBucketUser $associationBucketUser): static
    {
        if ($this->associationBucketUsers->removeElement($associationBucketUser)) {
            $associationBucketUser->removeUserId($this);
        }

        return $this;
    }
}
