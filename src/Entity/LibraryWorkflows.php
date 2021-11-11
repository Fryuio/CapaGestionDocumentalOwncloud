<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LibraryWorkflows
 *
 * @ORM\Table(name="library_workflows", indexes={@ORM\Index(name="fk_library_workflows_document_library1_idx", columns={"document_library_id"}), @ORM\Index(name="fk_library_workflows_workflows_types1_idx", columns={"workflows_types_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\LibraryWorkflowsRepository")
 */
class LibraryWorkflows
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
     * @ORM\Column(name="target", type="string", length=45, nullable=true)
     */
    private $target;

    /**
     * @var string|null
     *
     * @ORM\Column(name="path", type="string", length=45, nullable=true)
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
     * @var \WorkflowsTypes
     *
     * @ORM\ManyToOne(targetEntity="WorkflowsTypes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="workflows_types_id", referencedColumnName="id")
     * })
     */
    private $workflowsTypes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTarget(): ?string
    {
        return $this->target;
    }

    public function setTarget(?string $target): self
    {
        $this->target = $target;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
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

    public function getWorkflowsTypes(): ?WorkflowsTypes
    {
        return $this->workflowsTypes;
    }

    public function setWorkflowsTypes(?WorkflowsTypes $workflowsTypes): self
    {
        $this->workflowsTypes = $workflowsTypes;

        return $this;
    }


}
