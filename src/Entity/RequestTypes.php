<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RequestTypes
 *
 * @ORM\Table(name="request_types")
 * @ORM\Entity(repositoryClass="App\Repository\RequestTypesRepository")
 */
class RequestTypes
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
