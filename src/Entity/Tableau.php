<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TableauRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TableauRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Tableau
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"images_read"})
     */
    private $title;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"images_read"})
     */
    private $year;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"images_read"})
     */
    private $width;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"images_read"})
     */
    private $height;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"images_read"})
     */
    private $technique;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"images_read"})
     */
    private $surface;

    /**
     * @ORM\PrePersist
     */
    public function setSurfacePrePersist()
    {
        if (!empty($this->width) && !empty($this->height)) {
            $this->surface = $this->width*$this->height;
        }
        return null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = ucfirst($title);

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getTechnique(): ?string
    {
        return $this->technique;
    }

    public function setTechnique(?string $technique): self
    {
        $this->technique = ucfirst($technique);

        return $this;
    }

    public function getSurface(): ?int
    {
        return $this->surface;
    }
    
    public function setSurface(?int $surface): self
    {
        $this->surface = $surface;

        return $this;
    }
}
