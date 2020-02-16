<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ModeratorGroupRepository")
 */
class ModeratorGroup
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
     * @ORM\Column(type="string", length=255)
     */
    private $group_title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $group_username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $group_type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroupId(): ?int
    {
        return $this->group_id;
    }

    public function setGroupId(int $group_id): self
    {
        $this->group_id = $group_id;

        return $this;
    }

    public function getGroupTitle(): ?string
    {
        return $this->group_title;
    }

    public function setGroupTitle(string $group_title): self
    {
        $this->group_title = $group_title;

        return $this;
    }

    public function getGroupUsername(): ?string
    {
        return $this->group_username;
    }

    public function setGroupUsername(string $group_username): self
    {
        $this->group_username = $group_username;

        return $this;
    }

    public function getGroupType(): ?string
    {
        return $this->group_type;
    }

    public function setGroupType(string $group_type): self
    {
        $this->group_type = $group_type;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
