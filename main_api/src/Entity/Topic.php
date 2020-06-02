<?php
declare(strict_types=1);
namespace App\Entity;

use App\Repository\TopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TopicRepository::class)
 */
class Topic
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="topics")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Project $project;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isMainProjectTopic;

    public function __construct(bool $isMainProjectTopic = false)
    {
        $this->isMainProjectTopic = $isMainProjectTopic;
        $this->members = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreator()
    {
        return $this->creator;
    }

    public function setCreator($creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getIsMainProjectTopic(): ?bool
    {
        return $this->isMainProjectTopic;
    }

    public function setIsMainProjectTopic(bool $isMainProjectTopic): self
    {
        $this->isMainProjectTopic = $isMainProjectTopic;

        return $this;
    }
}
