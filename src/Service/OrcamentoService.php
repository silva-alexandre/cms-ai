<?php

namespace App\Service;

use App\Entity\Orcamento;

class OrcamentoService
{
    /**
     * Calcula e atualiza o valor total de um orçamento baseado no serviço selecionado e na quantidade.
     * 
     * @param Orcamento $orcamento
     * @return void
     */
    public function updateValorTotal(Orcamento $orcamento): void
    {
        $servico = $orcamento->getIdServico();
        $qtd = $orcamento->getQtd();

        if ($servico !== null && $qtd !== null) {
            $preco = $servico->getPreco();
            if ($preco !== null) {
                // Multiplicação de strings numéricas (decimal)
                $orcamento->setValorTotal((string) ((float) $qtd * (float) $preco));
            }
        }
    }
}
