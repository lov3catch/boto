<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ModeratorMemberRepository")
 */
class ModeratorMember
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $member_id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $member_is_bot;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $member_first_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $member_username;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMemberId(): ?int
    {
        return $this->member_id;
    }

    public function setMemberId(int $member_id): self
    {
        $this->member_id = $member_id;

        return $this;
    }

    public function getMemberIsBot(): ?bool
    {
        return $this->member_is_bot;
    }

    public function setMemberIsBot(bool $member_is_bot): self
    {
        $this->member_is_bot = $member_is_bot;

        return $this;
    }

    public function getMemberFirstName(): ?string
    {
        return $this->member_first_name;
    }

    public function setMemberFirstName(string $member_first_name): self
    {
        $this->member_first_name = $member_first_name;

        return $this;
    }

    public function getMemberUsername(): ?string
    {
        return $this->member_username;
    }

    public function setMemberUsername(string $member_username): self
    {
        $this->member_username = $member_username;

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
