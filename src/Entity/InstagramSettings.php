<?php

namespace App\Entity;

use App\Repository\InstagramSettingsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstagramSettingsRepository::class)]
class InstagramSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $accountId = null;

    #[ORM\Column(length: 255)]
    private ?string $accessToken = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccountId(): ?string
    {
        return $this->accountId;
    }

    public function setAccountId(string $accountId): self
    {
        $this->accountId = $accountId;

        return $this;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }
}
