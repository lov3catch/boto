<?php declare(strict_types=1);

namespace App\Botonarioum\TrackFinder;

class TrackFinderService
{
    public function search(string $searchThis, int $limit, int $offset): TrackFinderSearchResponse
    {
        return new TrackFinderSearchResponse($this->doSearch($searchThis, $limit, $offset));
    }

    private function doSearch(string $searchThis, int $limit, int $offset): array
    {
        $url = implode('', ['https://track-finder.herokuapp.com/search?query=', urlencode($searchThis), '&page[limit]=', $limit, '&page[offset]=', $offset]);
        $response = file_get_contents($url);

        return json_decode($response, true);
    }
}