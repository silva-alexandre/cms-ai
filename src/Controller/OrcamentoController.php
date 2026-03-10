<?php

namespace App\Controller;

use App\Entity\Orcamento;
use App\Form\OrcamentoType;
use App\Repository\OrcamentoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ServicoRepository;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\OrcamentoService;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/orcamento')]
final class OrcamentoController extends AbstractController
{
    #[Route(name: 'app_orcamento_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(OrcamentoRepository $orcamentoRepository): Response
    {
        $orcamentosEntidades = $orcamentoRepository->findBy([], ['data' => 'DESC']);
        
        $orcamentosSerialized = array_map(function($o) {
            return [
                'id' => $o->getId(),
                'titulo' => $o->getTitulo(),
                'qtd' => $o->getQtd(),
                'valorTotal' => $o->getValorTotal(),
                'data' => $o->getData() ? $o->getData()->format('Y-m-d H:i:s') : null,
                'id_servico' => $o->getIdServico() ? [
                    'id' => $o->getIdServico()->getId(),
                    'nome' => $o->getIdServico()->getNome()
                ] : null
            ];
        }, $orcamentosEntidades);

        return $this->render('orcamento/index.html.twig', [
            'orcamentos' => $orcamentosSerialized,
        ]);
    }

    #[Route('/new', name: 'app_orcamento_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager, ServicoRepository $servicoRepository, OrcamentoService $orcamentoService): Response
    {
        $orcamento = new Orcamento();
        $form = $this->createForm(OrcamentoType::class, $orcamento);
        $form->handleRequest($request);
        $servicos = $servicoRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $orcamentoService->updateValorTotal($orcamento);
            $entityManager->persist($orcamento);
            $entityManager->flush();

            return $this->redirectToRoute('app_orcamento_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('orcamento/new.html.twig', [
            'orcamento' => $orcamento,
            'form' => $form,
            'servicos' => $servicos,
        ]);
    }

    #[Route('/{id}', name: 'app_orcamento_show', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function show(Orcamento $orcamento): Response
    {
        return $this->render('orcamento/show.html.twig', [
            'orcamento' => $orcamento,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_orcamento_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Orcamento $orcamento, EntityManagerInterface $entityManager, ServicoRepository $servicoRepository, OrcamentoService $orcamentoService): Response
    {
        $form = $this->createForm(OrcamentoType::class, $orcamento);
        $form->handleRequest($request);
        $servicos = $servicoRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $orcamentoService->updateValorTotal($orcamento);
            $entityManager->flush();

            return $this->redirectToRoute('app_orcamento_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('orcamento/edit.html.twig', [
            'orcamento' => $orcamento,
            'form' => $form,
            'servicos' => $servicos,
        ]);
    }

    #[Route('/{id}', name: 'app_orcamento_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Orcamento $orcamento, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$orcamento->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($orcamento);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_orcamento_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/api/create', methods: ['GET'])]
    public function apiCreateRedirect(): Response
    {
        return $this->redirectToRoute('app_home');
    }

    #[Route('/api/create', name: 'app_orcamento_api_create', methods: ['POST'])]
    public function apiCreate(
        Request $request, 
        EntityManagerInterface $entityManager, 
        ServicoRepository $servicoRepository, 
        OrcamentoService $orcamentoService,
        ValidatorInterface $validator,
        CsrfTokenManagerInterface $csrfTokenManager
    ): Response
    {
        // 1. Validar CSRF Token
        $token = $request->headers->get('X-CSRF-TOKEN');
        if (!$csrfTokenManager->isTokenValid(new CsrfToken('orcamento_api', $token))) {
            return $this->json(['status' => 'error', 'message' => 'Token CSRF inválido.'], Response::HTTP_FORBIDDEN);
        }

        $data = json_decode($request->getContent(), true);

        // Honeypot check: if filled, it's a bot
        if (!empty($data['hp_email'])) {
            // Return success to trick the bot, but do nothing
            return $this->json(['status' => 'success', 'message' => 'Processed'], Response::HTTP_OK);
        }
        
        $orcamento = new Orcamento();
        $orcamento->setTitulo($data['titulo'] ?? 'Orçamento via Home');
        $orcamento->setQtd((string)($data['qtd'] ?? 0));
        
        if (isset($data['id_servico'])) {
            $servico = $servicoRepository->find($data['id_servico']);
            if ($servico) {
                $orcamento->setIdServico($servico);
            }
        }
        
        $orcamento->setData(new \DateTime());
        $orcamentoService->updateValorTotal($orcamento);

        // 2. Validar Entidade
        $errors = $validator->validate($orcamento);
        if (count($errors) > 0) {
            return $this->json([
                'status' => 'error', 
                'message' => (string) $errors
            ], Response::HTTP_BAD_REQUEST);
        }
        
        $entityManager->persist($orcamento);
        $entityManager->flush();
        
        return $this->json([
            'status' => 'success',
            'id' => $orcamento->getId(),
            'valor_total' => $orcamento->getValorTotal()
        ]);
    }
}
