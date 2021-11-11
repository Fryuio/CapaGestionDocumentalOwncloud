<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ObjectRequest
 *
 * @ORM\Table(name="object_request", indexes={@ORM\Index(name="fk_object_request_library_object1_idx", columns={"library_object_id"}), @ORM\Index(name="fk_object_request_request_types1_idx", columns={"request_types_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ObjectRequestRepository")
 */
class ObjectRequest
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
     * @var \LibraryObject
     *
     * @ORM\ManyToOne(targetEntity="LibraryObject")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="library_object_id", referencedColumnName="id")
     * })
     */
    private $libraryObject;

    /**
     * @var \RequestTypes
     *
     * @ORM\ManyToOne(targetEntity="RequestTypes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="request_types_id", referencedColumnName="id")
     * })
     */
    private $requestTypes;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRequestTypes(): ?RequestTypes
    {
        return $this->requestTypes;
    }

    public function setRequestTypes(?RequestTypes $requestTypes): self
    {
        $this->requestTypes = $requestTypes;

        return $this;
    }


}
