<?php

namespace App\Controller;

use App\Form\YoutubeUrlFormType;
use App\Service\YoutubeApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class YoutubeController extends AbstractController
{
    public function __construct(private YoutubeApiService $youtubeApiService)
    {

    }
    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(YoutubeUrlFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $playlistUrl = $data['playlistUrl'];
            parse_str(parse_url($playlistUrl,PHP_URL_QUERY),$query);
            $playlistId = $query['list'];

            if ($this->youtubeApiService->checkIfPlaylistExists($playlistId)){
                return $this->redirectToRoute('playlist_videos',[
                   'playlistId' => $playlistId
                ]);
            }else{
                $this->addFlash('error', 'Playlist not found.');
            }
        }

       return $this->render('youtube/index.html.twig',
       [
           'form' => $form
       ]);
    }

    #[Route('/show/videos/{playlistId}',name: 'playlist_videos')]
    public function showPlaylistVideos(String $playlistId): Response
    {
        $playlistInfo = $this->youtubeApiService->getPlaylistInfo($playlistId);
        return $this->render('youtube/playlist.html.twig',[
            'title' => $playlistInfo['title'],
            'description' => $playlistInfo['description'],
            'channelTitle' => $playlistInfo['channelTitle']
        ]);
    }


}
