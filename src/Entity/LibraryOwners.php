<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LibraryOwners
 *
 * @ORM\Table(name="library_owners", indexes={@ORM\Index(name="fk_library_owners_users1_idx", columns={"users_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\LibraryOwnersRepository")
 */
class LibraryOwners
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
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="users_id", referencedColumnName="id")
     * })
     */
    private $users;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): self
    {
        $this->users = $users;

        return $this;
    }


}
