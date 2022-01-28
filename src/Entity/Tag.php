<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 20)]
    private $name;

    #[ORM\Column(type: 'string', length: 10)]
    private $color;

    #[ORM\Column(type: 'boolean')]
    private $isActive;

    #[ORM\OneToMany(mappedBy: 'tag', targetEntity: TagInfo::class, orphanRemoval: true)]
    private $tagInfos;

    #[ORM\ManyToMany(targetEntity: Device::class, mappedBy: 'tags')]
    private $devices;

    public function __construct()
    {
        $this->tagInfos = new ArrayCollection();
        $this->devices = new ArrayCollection();
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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

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
     * @return Collection|TagInfo[]
     */
    public function getTagInfos(): Collection
    {
        return $this->tagInfos;
    }

    public function addTagInfo(TagInfo $tagInfo): self
    {
        if (!$this->tagInfos->contains($tagInfo)) {
            $this->tagInfos[] = $tagInfo;
            $tagInfo->setTag($this);
        }

        return $this;
    }

    public function removeTagInfo(TagInfo $tagInfo): self
    {
        if ($this->tagInfos->removeElement($tagInfo)) {
            // set the owning side to null (unless already changed)
            if ($tagInfo->getTag() === $this) {
                $tagInfo->setTag(null);
            }
        }

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
            $device->addTag($this);
        }

        return $this;
    }

    public function removeDevice(Device $device): self
    {
        if ($this->devices->removeElement($device)) {
            $device->removeTag($this);
        }

        return $this;
    }
}
