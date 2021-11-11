<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RequestApprovals
 *
 * @ORM\Table(name="request_approvals", indexes={@ORM\Index(name="fk_request_approvals_object_request1_idx", columns={"object_request_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\RequestApprovalsRepository")
 */
class RequestApprovals
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
     * @var \ObjectRequest
     *
     * @ORM\ManyToOne(targetEntity="ObjectRequest")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="object_request_id", referencedColumnName="id")
     * })
     */
    private $objectRequest;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjectRequest(): ?ObjectRequest
    {
        return $this->objectRequest;
    }

    public function setObjectRequest(?ObjectRequest $objectRequest): self
    {
        $this->objectRequest = $objectRequest;

        return $this;
    }


}
