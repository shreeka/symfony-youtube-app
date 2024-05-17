<?php

namespace App\Service;

use Google\Client;
use Google\Service\YouTube;

class YoutubeApiService
{
    private YouTube $youtubeService;
    public function __construct(private Client $client)
    {
        $youtubeService = new YouTube($this->client);
    }

    public function checkIfPlaylistExists(String $playlistId)
    {

    }
    public function connectWithYoutubeApi()
    {
        $ytService = new YouTube($this->client);
       // $response = $ytService->search->listSearch('snippet',['channelId' => 'UCDrDTfkgTBe7k6vnQeSM2Fg']);
        $response = $ytService->playlistItems->listPlaylistItems('snippet', [
            'playlistId' => 'PLOiq38KqgIJlseLvYLdifz-djhvwO_9Ex',
            'maxResults' => 10
        ]);
        foreach ($response->getItems() as $playlistItem) {
            dd($playlistItem) ;
        }

    }

}