<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}},
 *     denormalizationContext={"groups"={"write"}},
 *     collectionOperations={"get"},
 *     itemOperations={"get"}
 * )
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read"})
     */
    private $caption;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read"})
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Galerie", inversedBy="images")
     */
    private $galerie;

    private $source_path;

    private $galerie_content_path;

    /**
     * @ORM\OneToOne(targetEntity=Tableau::class, cascade={"persist", "remove"})
     * @Groups({"read"})
     */
    private $tableau;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $ordre;

    /**
     * Undocumented function
     *
     * @ORM\PostPersist
     * 
     */
    public function saveFileImgOnServer()
    {   
        $target_directory = getcwd() . $this->galerie_content_path;
        $target_path_file = getcwd() .$this->galerie_content_path .$this->url;
        if (!file_exists($target_directory)) {
            mkdir($target_directory);
        }
        move_uploaded_file($this->source_path, $target_path_file);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(?string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getGalerie(): ?Galerie
    {
        return $this->galerie;
    }

    public function setGalerie(?Galerie $galerie): self
    {
        $this->galerie = $galerie;

        return $this;
    }

    public function getSource_path()
    {
        return $this->source_path;
    }

    public function setSource_path($source_path)
    {
        $this->source_path = $source_path;

        return $this;
    }

    public function setGalerie_content_path($galerie_content_path)
    {
        $this->galerie_content_path = $galerie_content_path;

        return $this;
    }

    public function getTableau(): ?Tableau
    {
        return $this->tableau;
    }

    public function setTableau(?Tableau $tableau): self
    {
        $this->tableau = $tableau;

        return $this;
    }

    public function getOrdre(): ?float
    {
        return $this->ordre;
    }

    public function setOrdre(?float $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }
}
