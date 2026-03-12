<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use App\Repository\ServicoRepository;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ServicoRepository $servicoRepository): Response
    {
        $servicos = $servicoRepository->findAll();
        
        return $this->render('home/index.html.twig', [
            'servicos' => $servicos,
        ]);
    }


    #[Route('/blog', name: 'app_blog')]
    public function blog(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/contato', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/galeria', name: 'app_gallery')]
    public function gallery(): Response
    {
        return $this->render('home/gallery.html.twig');
    }
}
