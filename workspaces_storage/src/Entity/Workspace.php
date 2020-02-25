<?php
declare(strict_types=1);
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorkspaceRepository")
 */
class Workspace
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 30,
     *      minMessage = "Your workspace name must be at least {{ limit }} characters long",
     *      maxMessage = "Your workspace name cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=30)
     */
    private string $name;

    /**
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     * @Assert\Length(
     *     max = 60,
     *     maxMessage = "The email cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=60)
     */
    private string $adminEmail;

    /**
     * @Assert\Length(
     *      min = 6,
     *      max = 30,
     *      minMessage = "The admin password must be at least {{ limit }} characters long",
     *      maxMessage = "The admin password cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private string $adminPassword;

    /**
     * @Assert\Ip
     * @ORM\Column(type="string", length=15)
     */
    private string $IP;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $registrationDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $confirmationStatus;

    public function __construct()
    {
        $this->registrationDate = new \DateTime();
        $this->confirmationStatus = false;
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

    public function getAdminEmail(): ?string
    {
        return $this->adminEmail;
    }

    public function setAdminEmail(string $adminEmail): self
    {
        $this->adminEmail = $adminEmail;

        return $this;
    }

    public function getAdminPassword(): ?string
    {
        return $this->adminPassword;
    }

    public function setAdminPassword(string $adminPassword): self
    {
        $this->adminPassword = $adminPassword;

        return $this;
    }

    public function getIP(): ?string
    {
        return $this->IP;
    }

    public function setIP(string $IP): self
    {
        $this->IP = $IP;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTime
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(\DateTime $registrationDate): self
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    public function getConfirmationStatus(): ?bool
    {
        return $this->confirmationStatus;
    }

    public function setConfirmationStatus(bool $confirmationStatus): self
    {
        $this->confirmationStatus = $confirmationStatus;

        return $this;
    }
}
