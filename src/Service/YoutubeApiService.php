<?php

namespace App\Service;

use Google\Client;
use Google\Service\YouTube;

class YoutubeApiService
{
    private YouTube $youtubeService;
    public function __construct(private Client $client)
    {
        $this->youtubeService = new YouTube($this->client);
    }

    public function checkIfPlaylistExists(String $playlistId): bool
    {
        $response = $this->getPlaylistListResponse($playlistId);
        // A playlist that doesn't exist will have empty items in PlaylistListResponse
        return !empty($response->getItems());
    }

    public function getPlaylistInfo(String $playlistId): array
    {
       $response = $this->getPlaylistListResponse($playlistId);
       $playlist = $response->items[0]->snippet;
       return [
         'title' => $playlist->title,
         'description' => $playlist->description ?? null,
         'channelTitle' => $playlist->channelTitle
       ];

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

    /**
     * @param String $playlistId
     * @return YouTube\PlaylistListResponse
     * @throws \Google\Service\Exception
     */
    public function getPlaylistListResponse(string $playlistId): YouTube\PlaylistListResponse
    {
        $response = $this->youtubeService->playlists->listPlaylists('snippet',
            ['id' => $playlistId]);
        return $response;
    }

}