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
    private $max_words_count;

    /**
     * @ORM\Column(type="integer")
     */
    private $max_chars_count;

    /**
     * @ORM\Column(type="integer")
     */
    private $holdtime;

    /**
     * @ORM\Column(type="integer")
     */
    private $max_daily_messages_count;

    /**
     * @ORM\Column(type="integer")
     */
    private $min_referrals_count;

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

    public function getMaxWordsCount(): ?int
    {
        return $this->max_words_count;
    }

    public function setMaxWordsCount(int $max_words_count): self
    {
        $this->max_words_count = $max_words_count;

        return $this;
    }

    public function getMaxCharsCount(): ?int
    {
        return $this->max_chars_count;
    }

    public function setMaxCharsCount(int $max_chars_count): self
    {
        $this->max_chars_count = $max_chars_count;

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

    public function getMaxDailyMessagesCount(): ?int
    {
        return $this->max_daily_messages_count;
    }

    public function setMaxDailyMessagesCount(int $max_daily_messages_count): self
    {
        $this->max_daily_messages_count = $max_daily_messages_count;

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
}
