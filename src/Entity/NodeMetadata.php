<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NodeMetadata
 *
 * @ORM\Table(name="node_metadata", indexes={@ORM\Index(name="fk_node_metadata_library_node1_idx", columns={"library_node_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\NodeMetadataRepository")
 */
class NodeMetadata
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
     * @var string|null
     *
     * @ORM\Column(name="clave", type="string", length=45, nullable=true)
     */
    private $clave;

    /**
     * @var string|null
     *
     * @ORM\Column(name="valor", type="string", length=45, nullable=true)
     */
    private $valor;

    /**
     * @var \LibraryNode
     *
     * @ORM\ManyToOne(targetEntity="LibraryNode")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="library_node_id", referencedColumnName="id")
     * })
     */
    private $libraryNode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClave(): ?string
    {
        return $this->clave;
    }

    public function setClave(?string $clave): self
    {
        $this->clave = $clave;

        return $this;
    }

    public function getValor(): ?string
    {
        return $this->valor;
    }

    public function setValor(?string $valor): self
    {
        $this->valor = $valor;

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


}
