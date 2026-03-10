<?php

namespace App\Controller;

use App\Entity\Servico;
use App\Form\ServicoType;
use App\Repository\ServicoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/servico')]
final class ServicoController extends AbstractController
{
    #[Route(name: 'app_servico_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(ServicoRepository $servicoRepository): Response
    {
        return $this->render('servico/index.html.twig', [
            'servicos' => $servicoRepository->findAll(),
        ]);
    }

    #[Route('/api', name: 'api_servicos', methods: ['GET'])]

    public function listarServicos(ServicoRepository $servicoRepository): JsonResponse
    {
        $servicos = $servicoRepository->findAll();

        $data = [];
        foreach ($servicos as $servico) {
            $data[] = [
                'id' => $servico->getId(),
                'nome' => $servico->getNome(),
                'detalhe' => $servico->getDetalhe(),
                'preco' => $servico->getPreco(),
                'foto' => $servico->getFoto(), // Retorna a lista de imagens
            ];
        }

        return $this->json($data);
    }

    #[Route('/new', name: 'app_servico_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $servico = new Servico();
        $form = $this->createForm(ServicoType::class, $servico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fotoFiles = $form->get('foto')->getData();
            if ($fotoFiles) {
                $fotosNomes = $this->handleFileUpload($fotoFiles, $slugger);
                $servico->setFoto($fotosNomes);
            }

            $entityManager->persist($servico);
            $entityManager->flush();

            return $this->redirectToRoute('app_servico_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('servico/new.html.twig', [
            'servico' => $servico,
            'form'    => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_servico_show', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function show(Servico $servico): Response
    {
        return $this->render('servico/show.html.twig', [
            'servico' => $servico,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_servico_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Servico $servico, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ServicoType::class, $servico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fotoFiles = $form->get('foto')->getData();
            if ($fotoFiles) {
                $fotosNomes = $servico->getFoto() ?? [];
                $novasFotos = $this->handleFileUpload($fotoFiles, $slugger);
                $servico->setFoto(array_merge($fotosNomes, $novasFotos));
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_servico_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('servico/edit.html.twig', [
            'servico' => $servico,
            'form' => $form,
        ]);
    }

    private function handleFileUpload(array $fotoFiles, SluggerInterface $slugger): array
    {
        $fotosNomes = [];
        foreach ($fotoFiles as $fotoFile) {
            $originalFilename = pathinfo($fotoFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $fotoFile->guessExtension();

            try {
                $fotoFile->move(
                    $this->getParameter('uploads_directory'),
                    $newFilename
                );
                $fotosNomes[] = $newFilename;
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
        }
        return $fotosNomes;
    }

    #[Route('/{id}', name: 'app_servico_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Servico $servico, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $servico->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($servico);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_servico_index', [], Response::HTTP_SEE_OTHER);
    }
}
