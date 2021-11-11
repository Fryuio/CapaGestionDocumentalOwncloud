<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WorkflowExecutions
 *
 * @ORM\Table(name="workflow_executions", indexes={@ORM\Index(name="fk_workflow_executions_library_workflows1_idx", columns={"library_workflows_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\WorkflowExecutionsRepository")
 */
class WorkflowExecutions
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
     * @var \LibraryWorkflows
     *
     * @ORM\ManyToOne(targetEntity="LibraryWorkflows")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="library_workflows_id", referencedColumnName="id")
     * })
     */
    private $libraryWorkflows;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibraryWorkflows(): ?LibraryWorkflows
    {
        return $this->libraryWorkflows;
    }

    public function setLibraryWorkflows(?LibraryWorkflows $libraryWorkflows): self
    {
        $this->libraryWorkflows = $libraryWorkflows;

        return $this;
    }


}
