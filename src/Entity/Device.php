<?php

namespace App\Entity;

use App\Repository\DeviceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeviceRepository::class)]
class Device
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $serial;

    #[ORM\Column(type: 'string', length: 4, nullable: true)]
    private $manufactureYear;

    #[ORM\Column(type: 'date', nullable: true)]
    private $purchaseDate;

    #[ORM\Column(type: 'date', nullable: true)]
    private $manufacturerWarrantyEndDate;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\ManyToOne(targetEntity: Model::class, inversedBy: 'devices')]
    #[ORM\JoinColumn(nullable: false)]
    private $model;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'devices')]
    #[ORM\JoinColumn(nullable: false)]
    private $provider;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'devices')]
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSerial(): ?string
    {
        return $this->serial;
    }

    public function setSerial(string $serial): self
    {
        $this->serial = $serial;

        return $this;
    }

    public function getManufactureYear(): ?string
    {
        return $this->manufactureYear;
    }

    public function setManufactureYear(?string $manufactureYear): self
    {
        $this->manufactureYear = $manufactureYear;

        return $this;
    }

    public function getPurchaseDate(): ?\DateTimeInterface
    {
        return $this->purchaseDate;
    }

    public function setPurchaseDate(?\DateTimeInterface $purchaseDate): self
    {
        $this->purchaseDate = $purchaseDate;

        return $this;
    }

    public function getManufacturerWarrantyEndDate(): ?\DateTimeInterface
    {
        return $this->manufacturerWarrantyEndDate;
    }

    public function setManufacturerWarrantyEndDate(?\DateTimeInterface $manufacturerWarrantyEndDate): self
    {
        $this->manufacturerWarrantyEndDate = $manufacturerWarrantyEndDate;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(?Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getProvider(): ?Company
    {
        return $this->provider;
    }

    public function setProvider(?Company $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
