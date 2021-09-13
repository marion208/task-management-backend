<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="tasks")
 */

class Task
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id_task;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name_task;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_status_task;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_list_match;

    public function getIdTask(): ?int
    {
        return $this->id_task;
    }

    public function getNameTask(): ?string
    {
        return $this->name_task;
    }

    public function setNameTask(string $name_task): self
    {
        $this->name_task = $name_task;

        return $this;
    }

    public function getIdStatusTask(): ?int
    {
        return $this->id_status_task;
    }

    public function setIdStatusTask(int $id_status_task): self
    {
        $this->id_status_task = $id_status_task;

        return $this;
    }

    public function getIdListMatch(): ?int
    {
        return $this->id_list_match;
    }

    public function setIdListMatch(int $id_list_match): self
    {
        $this->id_list_match = $id_list_match;

        return $this;
    }
}
