<?php declare(strict_types=1);

namespace App\Botonarioum\TrackFinder;

class TrackFinderSearchResponse
{
    /**
     * @var Page
     */
    private $pager;

    /**
     * @var array
     */
    private $meta;

    /**
     * @var array
     */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data['data'];
        $this->meta = $data['meta'];
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getPager()
    {
        if ($this->pager instanceof Page) return $this->pager;

        $this->pager = Page::fromMeta($this->meta);

        return $this->getPager();
    }

    public function isEmpty(): bool
    {
        return empty($this->data);
    }
}