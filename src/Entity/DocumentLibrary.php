<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DocumentLibrary
 *
 * @ORM\Table(name="document_library", indexes={@ORM\Index(name="fk_document_library_library_owners1_idx", columns={"library_owners_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\DocumentLibraryRepository")
 */
class DocumentLibrary
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var \LibraryOwners
     *
     * @ORM\ManyToOne(targetEntity="LibraryOwners")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="library_owners_id", referencedColumnName="id")
     * })
     */
    private $libraryOwners;

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

    public function getLibraryOwners(): ?LibraryOwners
    {
        return $this->libraryOwners;
    }

    public function setLibraryOwners(?LibraryOwners $libraryOwners): self
    {
        $this->libraryOwners = $libraryOwners;

        return $this;
    }


}
