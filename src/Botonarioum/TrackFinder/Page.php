<?php declare(strict_types=1);

namespace App\Botonarioum\TrackFinder;

class Page
{
    private $offset;
    private $limit;
    private $count;

    public const
        PAGE_SIZE = 5,
        DIRECTION_CALLBACK_POSITION = 1,
        LIMIT_CALLBACK_POSITION = 3,
        OFFSET_CALLBACK_POSITION = 5,
        TEXT_CALLBACK_POSITION = 7;

    public const
        DEFAULT_LIMIT = 5,
        DEFAULT_OFFSET = 0;

    public function __construct(int $count, int $limit, int $offset)
    {
        $this->count = $count;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function limit(): int
    {
        return $this->limit;
    }

    public function offset(): int
    {
        return $this->offset;
    }

    public function hasPrev(): bool
    {
        return $this->offset + $this->limit > $this->limit;
    }

    public function hasNext(): bool
    {
        return $this->count - ($this->offset + $this->limit) >= $this->limit;
    }

    public static function fromMeta(array $meta): self
    {
        return new self($meta['total'], $meta['limit'], $meta['offset']);
    }
}
