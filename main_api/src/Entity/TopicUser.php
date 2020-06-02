<?php
declare(strict_types=1);
namespace App\Entity;

use App\Repository\TopicUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TopicUserRepository::class)
 */
class TopicUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user;

    /**
     * @ORM\ManyToOne(targetEntity=Topic::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Topic $topic;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $createdAt;

    public function __construct(?User $user = null, ?Topic $topic = null)
    {
        $this->user = $user;
        $this->topic = $topic;
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    public function setTopic(?Topic $topic): self
    {
        $this->topic = $topic;

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
}
