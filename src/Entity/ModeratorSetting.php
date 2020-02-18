<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ModeratorSettingRepository")
 */
class ModeratorSetting
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
    private $is_default;

    /**
     * @ORM\Column(type="integer")
     */
    private $max_message_words_count;

    /**
     * @ORM\Column(type="integer")
     */
    private $max_message_chars_count;

    /**
     * @ORM\Column(type="integer")
     */
    private $holdtime;

    /**
     * @ORM\Column(type="integer")
     */
    private $max_daily_message_count;

    /**
     * @ORM\Column(type="integer")
     */
    private $min_referrals_count;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $group_id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $allow_link;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $greeting_message;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $greeting_buttons;

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

    public function getIsDefault(): ?bool
    {
        return $this->is_default;
    }

    public function setIsDefault(bool $is_default): self
    {
        $this->is_default = $is_default;

        return $this;
    }

    public function getMaxMessageWordsCount(): ?int
    {
        return $this->max_message_words_count;
    }

    public function setMaxMessageWordsCount(int $max_message_words_count): self
    {
        $this->max_message_words_count = $max_message_words_count;

        return $this;
    }

    public function getMaxMessageCharsCount(): ?int
    {
        return $this->max_message_chars_count;
    }

    public function setMaxMessageCharsCount(int $max_message_chars_count): self
    {
        $this->max_message_chars_count = $max_message_chars_count;

        return $this;
    }

    public function getHoldtime(): ?int
    {
        return $this->holdtime;
    }

    public function setHoldtime(int $holdtime): self
    {
        $this->holdtime = $holdtime;

        return $this;
    }

    public function getMaxDailyMessageCount(): ?int
    {
        return $this->max_daily_message_count;
    }

    public function setMaxDailyMessageCount(int $max_daily_message_count): self
    {
        $this->max_daily_message_count = $max_daily_message_count;

        return $this;
    }

    public function getMinReferralsCount(): ?int
    {
        return $this->min_referrals_count;
    }

    public function setMinReferralsCount(int $min_referrals_count): self
    {
        $this->min_referrals_count = $min_referrals_count;

        return $this;
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

    public function getAllowLink(): ?bool
    {
        return $this->allow_link;
    }

    public function setAllowLink(bool $allow_link): self
    {
        $this->allow_link = $allow_link;

        return $this;
    }

    public function getGreetingMessage(): ?string
    {
        return $this->greeting_message;
    }

    public function setGreetingMessage(string $greeting_message): self
    {
        $this->greeting_message = $greeting_message;

        return $this;
    }

    public function getGreetingButtons(): ?string
    {
        return $this->greeting_buttons;
    }

    public function setGreetingButtons(string $greeting_buttons): self
    {
        $this->greeting_buttons = $greeting_buttons;

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