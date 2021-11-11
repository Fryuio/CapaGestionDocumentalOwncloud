<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AvailableRights
 *
 * @ORM\Table(name="available_rights", uniqueConstraints={@ORM\UniqueConstraint(name="right_UNIQUE", columns={"right"})})
 * @ORM\Entity(repositoryClass="App\Repository\AvailableRightsRepository")
 */
class AvailableRights
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
     * @ORM\Column(name="right", type="string", length=45, nullable=true)
     */
    private $right;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRight(): ?string
    {
        return $this->right;
    }

    public function setRight(?string $right): self
    {
        $this->right = $right;

        return $this;
    }


}
