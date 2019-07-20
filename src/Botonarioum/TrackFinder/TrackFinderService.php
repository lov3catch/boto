<?php declare(strict_types=1);

namespace App\Botonarioum\TrackFinder;

class TrackFinderService
{
    public function search(string $searchThis): TrackFinderSearchResponse
    {
        return new TrackFinderSearchResponse($this->doSearch($searchThis));
    }

    private function doSearch(string $searchThis): array
    {
        $response = file_get_contents('https://track-finder.herokuapp.com/search?query=hardkiss&page[limit]=5&page[offset]=0');
        return json_decode($response, true);
    }
}