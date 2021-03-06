<?php

namespace App\Entity\Gestapp;

use App\Entity\Admin\Employed;
use App\Entity\Gestapp\choice\CustomerChoice;
use App\Repository\Gestapp\CustomerRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Index(name: 'customer_idx', columns: ["first_name", "last_name"], flags: ['fulltext'])]

class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 25, nullable: true)]
    private $RefCustomer;

    #[ORM\Column(type: 'string', length: 80, nullable: true)]
    private $firstName;

    #[ORM\Column(type: 'string', length: 80, nullable: true)]
    private $lastName;

    #[ORM\Column(type: 'string', length: 125)]
    private $slug;

    #[ORM\ManyToOne(targetEntity: CustomerChoice::class)]
    private $customerChoice;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $adress;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $complement;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    private $zipcode;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $city;

    #[ORM\Column(type: 'string', length: 14, nullable: true)]
    private $home;

    #[ORM\Column(type: 'string', length: 14, nullable: true)]
    private $desk;

    #[ORM\Column(type: 'string', length: 14)]
    private $gsm;

    #[ORM\Column(type: 'string', length: 14, nullable: true)]
    private $fax;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $otherEmail;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $facebook;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $instagram;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $linkedin;

    #[ORM\Column(type: 'boolean')]
    private $isArchived = false;

    #[ORM\ManyToOne(targetEntity: Employed::class, inversedBy: 'Customer')]
    #[ORM\JoinColumn(nullable: false)]
    private $refEmployed;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'refApplicant')]
    private $applicant;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'refAcquirer')]
    private $acquirer;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    #[ORM\ManyToMany(targetEntity: Property::class, mappedBy: 'Customer')]
    private $properties;

    /**
     * Permet d'initialiser le slug !
     * Utilisation de slugify pour transformer une chaine de caract??res en slug
     */
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function initializeSlug() {
        $slugify = new Slugify();
        $this->slug = $slugify->slugify($this->firstName."_".$this->lastName);
    }

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->properties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefCustomer(): ?string
    {
        return $this->RefCustomer;
    }

    public function setRefCustomer(?string $RefCustomer): self
    {
        $this->RefCustomer = $RefCustomer;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCustomerChoice(): ?CustomerChoice
    {
        return $this->customerChoice;
    }

    public function setCustomerChoice(?CustomerChoice $customerChoice): self
    {
        $this->customerChoice = $customerChoice;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(?string $complement): self
    {
        $this->complement = $complement;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getHome(): ?string
    {
        return $this->home;
    }

    public function setHome(?string $home): self
    {
        $this->home = $home;

        return $this;
    }

    public function getDesk(): ?string
    {
        return $this->desk;
    }

    public function setDesk(?string $desk): self
    {
        $this->desk = $desk;

        return $this;
    }

    public function getGsm(): ?string
    {
        return $this->gsm;
    }

    public function setGsm(string $gsm): self
    {
        $this->gsm = $gsm;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): self
    {
        $this->fax = $fax;

        return $this;
    }

    public function getOtherEmail(): ?string
    {
        return $this->otherEmail;
    }

    public function setOtherEmail(?string $otherEmail): self
    {
        $this->otherEmail = $otherEmail;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getIsArchived(): ?bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(bool $isArchived): self
    {
        $this->isArchived = $isArchived;

        return $this;
    }

    public function getRefEmployed(): ?Employed
    {
        return $this->refEmployed;
    }

    public function setRefEmployed(?Employed $refEmployed): self
    {
        $this->refEmployed = $refEmployed;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTime('now');

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTime('now');

        return $this;
    }

    public function getApplicant(): ?Project
    {
        return $this->applicant;
    }

    public function setProject(?Project $applicant): self
    {
        $this->applicant = $applicant;

        return $this;
    }

    public function getAcquirer(): ?Project
    {
        return $this->acquirer;
    }

    public function setAcquirer(?Project $acquirer): self
    {
        $this->acquirer = $acquirer;

        return $this;
    }

    public function __toString()
    {
        return $this->firstName." ".$this->lastName;
    }

    /**
     * @return Collection<int, Property>
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }

    public function addProperty(Property $property): self
    {
        if (!$this->properties->contains($property)) {
            $this->properties[] = $property;
            $property->addCustomer($this);
        }

        return $this;
    }

    public function removeProperty(Property $property): self
    {
        if ($this->properties->removeElement($property)) {
            $property->removeCustomer($this);
        }

        return $this;
    }
}
