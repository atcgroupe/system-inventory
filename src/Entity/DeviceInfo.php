<?php

namespace App\Entity;

use App\Repository\DeviceInfoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeviceInfoRepository::class)]
class DeviceInfo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $value;

    #[ORM\ManyToOne(targetEntity: TagInfo::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $tag;

    #[ORM\ManyToOne(targetEntity: Device::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $device;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getTag(): ?TagInfo
    {
        return $this->tag;
    }

    public function setTag(?TagInfo $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    public function getDevice(): ?Device
    {
        return $this->device;
    }

    public function setDevice(?Device $device): self
    {
        $this->device = $device;

        return $this;
    }
}
