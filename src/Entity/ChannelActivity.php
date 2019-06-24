<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChannelActivityRepository")
 */
class ChannelActivity
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
    private $channel_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $handler_name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function setChannelId(int $channel_id): self
    {
        $this->channel_id = $channel_id;

        return $this;
    }

    public function setHandlerName(string $handler_name): self
    {
        $this->handler_name = $handler_name;

        return $this;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
