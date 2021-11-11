<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LibraryNode
 *
 * @ORM\Table(name="library_node", indexes={@ORM\Index(name="fk_library_node_library_node1_idx", columns={"parent_id"}), @ORM\Index(name="fk_library_node_document_library1_idx", columns={"document_library_id"}), @ORM\Index(name="fk_library_node_library_object1_idx", columns={"library_object_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\LibraryNodeRepository")
 */
class LibraryNode
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
     * @var \DocumentLibrary
     *
     * @ORM\ManyToOne(targetEntity="DocumentLibrary")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="document_library_id", referencedColumnName="id")
     * })
     */
    private $documentLibrary;

    /**
     * @var \LibraryNode
     *
     * @ORM\ManyToOne(targetEntity="LibraryNode")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;

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

    public function getDocumentLibrary(): ?DocumentLibrary
    {
        return $this->documentLibrary;
    }

    public function setDocumentLibrary(?DocumentLibrary $documentLibrary): self
    {
        $this->documentLibrary = $documentLibrary;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

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
