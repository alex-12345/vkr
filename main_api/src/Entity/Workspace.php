<?php

namespace App\Entity;

use App\Repository\WorkspaceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WorkspaceRepository::class)
 */
class Workspace
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $db_correct;

    /**
     * @ORM\Column(type="boolean")
     */
    private $mail_server_correct;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDbCorrect(): ?bool
    {
        return $this->db_correct;
    }

    public function setDbCorrect(bool $db_correct): self
    {
        $this->db_correct = $db_correct;

        return $this;
    }

    public function getMailServerCorrect(): ?bool
    {
        return $this->mail_server_correct;
    }

    public function setMailServerCorrect(bool $mail_server_correct): self
    {
        $this->mail_server_correct = $mail_server_correct;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
