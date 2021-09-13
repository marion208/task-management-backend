<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="status")
 */

class ManageStatus
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $id_status;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $name_status;

    public function getIdStatus(): ?int
    {
        return $this->id_status;
    }

    public function getNameStatus(): ?string
    {
        return $this->name_status;
    }

    public function setNameStatus(string $name_status): self
    {
        $this->name_status = $name_status;

        return $this;
    }
}
