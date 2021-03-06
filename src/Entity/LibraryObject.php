<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LibraryObject
 *
 * @ORM\Table(name="library_object")
 * @ORM\Entity(repositoryClass="App\Repository\LibraryObjectRepository")
 */
class LibraryObject
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }


}
