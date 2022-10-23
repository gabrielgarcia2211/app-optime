<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @UniqueEntity(fields="code", message="Este valor ya existe")
 * @UniqueEntity(fields="name", message="Este valor ya existe")
 */
class Product
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="code", type="string", length=20, unique=true)
     * @Assert\NotNull()
     * @Assert\NotBlank(message="Este valor no debe estar en blanco")
     * @Assert\Regex(pattern= "/^[a-z0-9]+$/i", message="No puede tener caracteres especiales")
     * @Assert\Length(
     *    min = 4,
     *    max = 10,
     *    minMessage = "Debe tener al menos {{ limit }} caracteres",
     *    maxMessage = "No puede ser mayor a {{ limit }} caracteres"
     * )
     */
    private $code;

    /**
     * @ORM\Column(name="name", type="string", length=125, unique=true)
     * @Assert\NotNull()
     * @Assert\NotBlank(message="Este valor no debe estar en blanco")
     * @Assert\Length(
     *    min = 4,
     *    minMessage = "Debe tener al menos {{ limit }} caracteres",
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotNull()
     * @Assert\NotBlank(message="Este valor no debe estar en blanco")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull()
     * @Assert\NotBlank(message="Este valor no debe estar en blanco")
     */
    private $brand;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull()
     * @Assert\NotBlank(message="Este valor no debe estar en blanco")
     */
    private $category;


    /**
     * @ORM\Column(type="integer", length=30)
     * @Assert\NotBlank(message="Este valor no debe estar en blanco")
     */
    private $price;

     /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode($code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand($brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }


    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
