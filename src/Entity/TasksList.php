<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="tasks_list")
 */

class TasksList
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id_list;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name_list;

    public function getIdList(): ?int
    {
        return $this->id_list;
    }

    public function getNameList(): ?string
    {
        return $this->name_list;
    }

    public function setNameList(string $name_list): self
    {
        $this->name_list = $name_list;

        return $this;
    }
}
