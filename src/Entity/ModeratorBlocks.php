<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ModeratorBlocksRepository")
 */
class ModeratorBlocks
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     */
    private $group_id;

    /**
     * @ORM\Column(type="bigint")
     */
    private $user_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $strategy;

    /**
     * @ORM\Column(type="bigint")
     */
    private $admin_id;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroupId(): ?string
    {
        return $this->group_id;
    }

    public function setGroupId(string $group_id): self
    {
        $this->group_id = $group_id;

        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->user_id;
    }

    public function setUserId(string $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getStrategy(): ?string
    {
        return $this->strategy;
    }

    public function setStrategy(string $strategy): self
    {
        $this->strategy = $strategy;

        return $this;
    }

    public function getAdminId(): ?int
    {
        return $this->admin_id;
    }

    public function setAdminId(int $admin_id): self
    {
        $this->admin_id = $admin_id;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
