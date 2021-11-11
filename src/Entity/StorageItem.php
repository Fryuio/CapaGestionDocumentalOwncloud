<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StorageItem
 *
 * @ORM\Table(name="storage_item", indexes={@ORM\Index(name="fk_storage_item_storages1_idx", columns={"storages_id"}), @ORM\Index(name="fk_storage_item_document1_idx", columns={"document_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\StorageItemRepository")
 */
class StorageItem
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
     * @ORM\Column(name="path", type="string", length=254, nullable=false)
     */
    private $path;

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
     * @var \Storages
     *
     * @ORM\ManyToOne(targetEntity="Storages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="storages_id", referencedColumnName="id")
     * })
     */
    private $storages;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getStorages(): ?Storages
    {
        return $this->storages;
    }

    public function setStorages(?Storages $storages): self
    {
        $this->storages = $storages;

        return $this;
    }


}
