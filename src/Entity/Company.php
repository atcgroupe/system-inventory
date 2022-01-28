<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $name;

    #[ORM\Column(type: 'boolean')]
    private $isProvider;

    #[ORM\Column(type: 'boolean')]
    private $isManufacturer;

    #[ORM\Column(type: 'string', length: 12, nullable: true)]
    private $technicalDepartmentPhone;

    #[ORM\Column(type: 'text', nullable: true)]
    private $technicalDepartmentProcedure;

    #[ORM\Column(type: 'boolean')]
    private $isActive;

    #[ORM\OneToMany(mappedBy: 'provider', targetEntity: Device::class)]
    private $devices;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Contact::class, orphanRemoval: true)]
    private $contacts;

    #[ORM\OneToMany(mappedBy: 'provider', targetEntity: Contract::class)]
    private $contracts;

    public function __construct()
    {
        $this->devices = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->contracts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsProvider(): ?bool
    {
        return $this->isProvider;
    }

    public function setIsProvider(bool $isProvider): self
    {
        $this->isProvider = $isProvider;

        return $this;
    }

    public function getIsManufacturer(): ?bool
    {
        return $this->isManufacturer;
    }

    public function setIsManufacturer(bool $isManufacturer): self
    {
        $this->isManufacturer = $isManufacturer;

        return $this;
    }

    public function getTechnicalDepartmentPhone(): ?string
    {
        return $this->technicalDepartmentPhone;
    }

    public function setTechnicalDepartmentPhone(?string $technicalDepartmentPhone): self
    {
        $this->technicalDepartmentPhone = $technicalDepartmentPhone;

        return $this;
    }

    public function getTechnicalDepartmentProcedure(): ?string
    {
        return $this->technicalDepartmentProcedure;
    }

    public function setTechnicalDepartmentProcedure(?string $technicalDepartmentProcedure): self
    {
        $this->technicalDepartmentProcedure = $technicalDepartmentProcedure;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection|Device[]
     */
    public function getDevices(): Collection
    {
        return $this->devices;
    }

    public function addDevice(Device $device): self
    {
        if (!$this->devices->contains($device)) {
            $this->devices[] = $device;
            $device->setProvider($this);
        }

        return $this;
    }

    public function removeDevice(Device $device): self
    {
        if ($this->devices->removeElement($device)) {
            // set the owning side to null (unless already changed)
            if ($device->getProvider() === $this) {
                $device->setProvider(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Contact[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setCompany($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getCompany() === $this) {
                $contact->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Contract[]
     */
    public function getContracts(): Collection
    {
        return $this->contracts;
    }

    public function addContract(Contract $contract): self
    {
        if (!$this->contracts->contains($contract)) {
            $this->contracts[] = $contract;
            $contract->setProvider($this);
        }

        return $this;
    }

    public function removeContract(Contract $contract): self
    {
        if ($this->contracts->removeElement($contract)) {
            // set the owning side to null (unless already changed)
            if ($contract->getProvider() === $this) {
                $contract->setProvider(null);
            }
        }

        return $this;
    }
}
