<?php

namespace App\Entity;

use App\Repository\LogementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LogementRepository::class)
 * @UniqueEntity("adresse",message="l'adresse doit être unique!")
 */
class Logement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *  @Assert\Range(min="1",minMessage="la superficie doit être positive!",groups={"modifier"})
     * @ORM\Column(type="float")
     */
    private $superficie;

    /**
     *@Assert\Range(min="1",minMessage="le nombre de pièces doit être positif!",groups={"modifier"})
     * @ORM\Column(type="integer")
     */
    private $nombrePieces;

    /**
     *@Assert\Choice(choices={"maison","appartement","yourte"},groups={"modifier"})
     * @ORM\Column(type="string", length=50)
     */
    private $typeLogement;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $adresse;

    /**
     * @ORM\Column(type="boolean")
     */
    private $piscine;

    /**
     * @Assert\Range(min="1",minMessage="la superficie doit être positive",groups={"modifier"})
     * @ORM\Column(type="float", nullable=true)
     */
    private $exterieur;

    /**
     * @ORM\Column(type="boolean")
     */
    private $garage;

    /**
     * @Assert\Choice(choices = {"vente","location"},groups={"modifier"})
     * @ORM\Column(type="string", length=15)
     */
    private $typeVente;

    /**
     * @Assert\Range(min="1",minMessage="le prix doit être positif",groups={"modifier"})
     * @ORM\Column(type="integer")
     */
    private $prix;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateParution;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModification;

    /**
     * @Assert\File(maxSize="2048k",maxSizeMessage="l'image doit peser au maximum 2 mo",mimeTypes={"image/png","image/jpeg"},mimeTypesMessage="l'image doit être au format JPEG ou PNG",groups={"modifier"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSuperficie(): ?float
    {
        return $this->superficie;
    }

    public function setSuperficie(float $superficie): self
    {
        $this->superficie = $superficie;

        return $this;
    }

    public function getNombrePieces(): ?int
    {
        return $this->nombrePieces;
    }

    public function setNombrePieces(int $nombrePieces): self
    {
        $this->nombrePieces = $nombrePieces;

        return $this;
    }

    public function getTypeLogement(): ?string
    {
        return $this->typeLogement;
    }

    public function setTypeLogement(string $typeLogement): self
    {
        $this->typeLogement = $typeLogement;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function isPiscine(): ?bool
    {
        return $this->piscine;
    }

    public function setPiscine(bool $piscine): self
    {
        $this->piscine = $piscine;

        return $this;
    }

    public function getExterieur(): ?float
    {
        return $this->exterieur;
    }

    public function setExterieur(?float $exterieur): self
    {
        $this->exterieur = $exterieur;

        return $this;
    }

    public function isGarage(): ?bool
    {
        return $this->garage;
    }

    public function setGarage(bool $garage): self
    {
        $this->garage = $garage;

        return $this;
    }

    public function getTypeVente(): ?string
    {
        return $this->typeVente;
    }

    public function setTypeVente(string $typeVente): self
    {
        $this->typeVente = $typeVente;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDateParution(): ?\DateTimeInterface
    {
        return $this->dateParution;
    }

    public function setDateParution(\DateTimeInterface $dateParution): self
    {
        $this->dateParution = $dateParution;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->dateModification;
    }

    public function setDateModification(?\DateTimeInterface $dateModification): self
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
