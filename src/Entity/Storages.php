<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Storages
 *
 * @ORM\Table(name="storages", indexes={@ORM\Index(name="fk_storages_storage_types1_idx", columns={"storage_types_id"}), @ORM\Index(name="fk_storages_document_library1_idx", columns={"document_library_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\StoragesRepository")
 */
class Storages
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
     * @ORM\Column(name="metadata", type="text", length=0, nullable=true)
     */
    private $metadata;

    /**
     * @var string
     *
     * @ORM\Column(name="storage_path", type="string", length=254, nullable=false)
     */
    private $storagePath;

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
     * @var \StorageTypes
     *
     * @ORM\ManyToOne(targetEntity="StorageTypes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="storage_types_id", referencedColumnName="id")
     * })
     */
    private $storageTypes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMetadata(): ?string
    {
        return $this->metadata;
    }

    public function setMetadata(?string $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function getStoragePath(): ?string
    {
        return $this->storagePath;
    }

    public function setStoragePath(string $storagePath): self
    {
        $this->storagePath = $storagePath;

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

    public function getStorageTypes(): ?StorageTypes
    {
        return $this->storageTypes;
    }

    public function setStorageTypes(?StorageTypes $storageTypes): self
    {
        $this->storageTypes = $storageTypes;

        return $this;
    }


}
