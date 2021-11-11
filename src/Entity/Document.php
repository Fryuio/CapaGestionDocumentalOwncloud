<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Document
 *
 * @ORM\Table(name="document", indexes={@ORM\Index(name="fk_document_document1_idx", columns={"document_id"}), @ORM\Index(name="fk_document_library_node1_idx", columns={"library_node_id"}), @ORM\Index(name="fk_document_library_object1_idx", columns={"library_object_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\DocumentRepository")
 */
class Document
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
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=45, nullable=false)
     */
    private $path;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="last_version", type="boolean", nullable=true)
     */
    private $lastVersion;

    /**
     * @var \Document
     *
     * @ORM\ManyToOne(targetEntity="Document")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="document_id", referencedColumnName="id")
     * })
     */
    private $document;

    /**
     * @var \LibraryNode
     *
     * @ORM\ManyToOne(targetEntity="LibraryNode")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="library_node_id", referencedColumnName="id")
     * })
     */
    private $libraryNode;

    /**
     * @var \LibraryObject
     *
     * @ORM\ManyToOne(targetEntity="LibraryObject")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="library_object_id", referencedColumnName="id")
     * })
     */
    private $libraryObject;

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

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getLastVersion(): ?bool
    {
        return $this->lastVersion;
    }

    public function setLastVersion(?bool $lastVersion): self
    {
        $this->lastVersion = $lastVersion;

        return $this;
    }

    public function getDocument(): ?self
    {
        return $this->document;
    }

    public function setDocument(?self $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getLibraryNode(): ?LibraryNode
    {
        return $this->libraryNode;
    }

    public function setLibraryNode(?LibraryNode $libraryNode): self
    {
        $this->libraryNode = $libraryNode;

        return $this;
    }

    public function getLibraryObject(): ?LibraryObject
    {
        return $this->libraryObject;
    }

    public function setLibraryObject(?LibraryObject $libraryObject): self
    {
        $this->libraryObject = $libraryObject;

        return $this;
    }


}
