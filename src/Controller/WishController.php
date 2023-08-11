<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/wish', name: 'wish_')]
class WishController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(
        WishRepository $wishRepository,
        HttpClientInterface $client
    ): Response
    {
        // Fetch API
        $response = $client->request(
            'GET',
            'https://api.chucknorris.io/jokes/random'
        );

        $blagues = $response->toArray();
//        dd($blagues);
        // Récupérer la liste des souhaits dans la bdd
        $wishList = $wishRepository->findBy(
            ["isPublished" => true],
            ["dateCreated" => "ASC"]
        );

        return $this->render(
            'wish/list.html.twig',
                compact('wishList',
                    'blagues')
        );
    }
    #[Route('/detail/{wish}', name: 'detail', requirements: ["wish" => "\d+"])]
    public function detail(
        Wish $wish
    ): Response
    {
        return $this->render(
            'wish/detail.html.twig',
            compact('wish')
        );
    }

    #[Route('/creation', name: 'creation')]
    public function create(
        WishRepository $wishRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        // Récupérer la liste des souhaits dans la bdd
        $wishList = $wishRepository->findBy(
            ["isPublished" => true],
            ["dateCreated" => "ASC"]
        );

        return $this->render(
            'wish/list.html.twig',
            compact('wishList')
        );
    }
}
