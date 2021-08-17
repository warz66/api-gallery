<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use App\Controller\GalerieReturnController;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GalerieRepository")
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"title"}, message="Une autre galerie possède déjà ce titre, merci de le modifier")
 * @ApiResource(
 *     normalizationContext={"groups"={"galeries_read", "galeries_read_images"}},
 *     collectionOperations={
 *      "galeries"={
 *          "method"="get", 
 *          "path"="/galeries",
 *          "controller"=App\Controller\UpdateGaleriesWithoutImgsController::class,
 *          "normalization_context"={
 *              "groups"="galeries_read"
 *          },
 *          "openapi_context"={
 *              "summary"="qsdfgqsdfsqdf",
 *              "description"="sdfqgqdsfgsdfg"
 *          },
 *      },
 *      "galeries-images"={
 *          "method"="get", 
 *          "path"="/galeries-images",
 *          "controller"=App\Controller\UpdateGaleriesController::class, 
 *          "normalization_context"={
 *              "groups"="galeries_read_images"
 *          },
 *          "openapi_context"={
 *              "summary"="qsdfgqsdfsqdf",
 *              "description"="sdfqgqdsfgsdfg"
 *      },
 *     }
 *    },
 *     itemOperations={"galerie"={
 *      "method"="get", 
 *      "path"="/galerie/{slug}", 
 *      "defaults"={"identifiedBy"="slug"},
 *      "controller"=App\Controller\UpdateGalerieController::class, 
 *      "openapi_context"={
 *          "summary"="qsdfgqsdfsqdf",
 *          "description"="sdfqgqdsfgsdfg"
 *      },
 *     }
 *    },
 * )
 * @ApiFilter(SearchFilter::class, properties={"slug", "reference", "image.tableau.year", "id"})
 * @ApiFilter(OrderFilter::class, properties={"images.ordre"})
 */
class Galerie
{   

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"galeries_read_images", "galeries_read"})
     * @ApiProperty(identifier=false)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max=100, maxMessage="Le titre ne peut pas faire plus de 100 caractères")
     * @Assert\NotBlank
     * @Groups({"galeries_read_images", "galeries_read"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max=255, maxMessage="La description ne peut pas faire plus de 255 caractères")
     * @Groups({"galeries_read_images", "galeries_read"})
     */
    private $description;

    /**
     * @Groups({"galeries_read"})
     * @Groups({"galeries_read_images", "galeries_read"})
     */
    private $pathImgCover;

    /**
     * @Groups({"galeries_read"})
     * @Groups({"galeries_read_images", "galeries_read"})
     */
    private $pathImgCoverCache;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cover_image;

    /**
     * @var file|null
     * @Vich\UploadableField(mapping="cover_image_galerie", fileNameProperty="cover_image")
     * @Assert\File(maxSize="1M", mimeTypes = {"image/jpeg", "image/png"})
     */
    private $imageFile;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="galerie", orphanRemoval=true, cascade={"persist"})
     * @Groups({"galeries_read_images"})
     */
    private $images;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"galeries_read_images", "galeries_read"}) 
     * @ApiProperty(identifier=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut;

    /**
     * @ORM\Column(type="boolean")
     */
    private $trash = 0;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $par_ordre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"galeries_read"})
     */
    private $theme;

    /**
     * Permet de contraindre la validation si une image de couverture n'a pas été ajouté 
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        if (!isset($this->cover_image) && !isset($this->imageFile)) {
            $context->buildViolation('Veuillez ajouter une image de couverture')
                ->atPath('imageFile')
                ->addViolation();
        }
    }

    /**
     * Get the value of imageFile
     *
     * @return  file|null
     */ 
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set the value of imageFile
     *
     * @param  file|null  $imageFile
     *
     * @return  self
     */ 
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->updated_at = new \DateTime('now');    
        }

        return $this;
    }

    /**
     * Permet d'initiliser la date de création
     * 
     * @ORM\PrePersist
     * 
     */
    public function initCreateAt() {
        if(!isset($this->create_at)) {
            $this->create_at = new \DateTime('now');
        }
    }

    /**
     * Permet d'initiliser le statut
     * 
     * @ORM\PrePersist
     * 
     */
    public function initStatut() {
        if(!isset($this->statut)) {
            $this->statut = 0;
        }
    }

    /**
     * Permet d'initiliser le slug ! 
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     */
    public function initializeSlug() {
        $slugify = new Slugify();
        $this->slug = $slugify->slugify($this->title);
    }

    /**
     * Met à jour la date à chaque modification 
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     */
    public function refreshUpdatedAt() {
        $this->updated_at = new \DateTime('now');
    }
    
    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->cover_image;
    }

    public function setCoverImage(?string $cover_image): self
    {
        $this->cover_image = $cover_image;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function setImages(Collection $images): self
    {
        $this->images = $images;

        return $this;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setGalerie($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getGalerie() === $this) {
                $image->setGalerie(null);
            }
        }

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

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTimeInterface $create_at): self
    {
        $this->create_at = $create_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getTrash(): ?bool
    {
        return $this->trash;
    }

    public function setTrash(bool $trash): self
    {
        $this->trash = $trash;

        return $this;
    }

    public function getParOrdre(): ?string
    {
        return $this->par_ordre;
    }

    public function setParOrdre(string $par_ordre): self
    {
        $this->par_ordre = $par_ordre;

        return $this;
    }

    /**
     * Get the value of pathImgCover
     */ 
    public function getPathImgCover()
    {
        return $this->pathImgCover;
    }

    /**
     * Set the value of pathImgCover
     *
     * @return  self
     */ 
    public function setPathImgCover($pathImgCover)
    {
        $this->pathImgCover = $pathImgCover;

        return $this;
    }

    /**
     * Get the value of pathImgCoverCache
     */ 
    public function getPathImgCoverCache()
    {
        return $this->pathImgCoverCache;
    }

    /**
     * Set the value of pathImgCoverCache
     *
     * @return  self
     */ 
    public function setPathImgCoverCache($pathImgCoverCache)
    {
        $this->pathImgCoverCache = $pathImgCoverCache;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(?string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }
}
