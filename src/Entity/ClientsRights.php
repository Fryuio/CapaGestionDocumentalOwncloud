<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientsRights
 *
 * @ORM\Table(name="clients_rights", indexes={@ORM\Index(name="fk_clients_rights_available_rights1_idx", columns={"available_rights_id"}), @ORM\Index(name="fk_clients_rights_library_object1_idx", columns={"library_object_id"}), @ORM\Index(name="fk_clients_rights_clients1_idx", columns={"clients_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ClientsRightsRepository")
 */
class ClientsRights
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
     * @var \AvailableRights
     *
     * @ORM\ManyToOne(targetEntity="AvailableRights")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="available_rights_id", referencedColumnName="id")
     * })
     */
    private $availableRights;

    /**
     * @var \Clients
     *
     * @ORM\ManyToOne(targetEntity="Clients")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="clients_id", referencedColumnName="id")
     * })
     */
    private $clients;

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

    public function getAvailableRights(): ?AvailableRights
    {
        return $this->availableRights;
    }

    public function setAvailableRights(?AvailableRights $availableRights): self
    {
        $this->availableRights = $availableRights;

        return $this;
    }

    public function getClients(): ?Clients
    {
        return $this->clients;
    }

    public function setClients(?Clients $clients): self
    {
        $this->clients = $clients;

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
