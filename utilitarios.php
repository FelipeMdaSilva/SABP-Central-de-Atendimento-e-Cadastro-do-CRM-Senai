<?php
declare(strict_types=1);

$clientes = [
    [
        "nome" => "  ANA CLARA SILVA ",
        "cpf" => "123.456.789-00",
        "email" => "ana.clara@email.com",
        "contrato" => 1500.00,
        "ativo" => true
    ],
    [
        "nome" => "Carlos Souza",
        "cpf" => "987.654.321-00",
        "email" => "carlos.souza@email.com",
        "contrato" => 850.50,
        "ativo" => false
    ],
    [
        "nome" => "Ricardo Teixeira",
        "cpf" => "111.222.333-44",
        "email" => "ricardo.teixeira@email.com",
        "contrato" => 1200.50,
        "ativo" => true
    ],
    [
        "nome" => "Maria Silva",
        "cpf" => "456.321.789-00",
        "email" => "maria.silva@email.com",
        "contrato" => 1050.00,
        "ativo" => false
    ]
];

// Requisito 1: Listagem
foreach ($clientes as $cliente) {
    echo "Nome: " . $cliente['nome'] . "<br>";
    echo "CPF: " . $cliente['cpf'] . "<br>";
    echo "E-mail: " . $cliente['email'] . "<br>";
    echo "Valor do Contrato: R$ " . $cliente['contrato'] . "<br>";
    echo "Situação: " . ($cliente['ativo'] ? "Ativo" : "Inativo") . "<br><br>";
}

// Requisito 2: Busca por nome
function buscarCliente(array $clientes, string $nome): ?array {
    foreach ($clientes as $cliente) {
        if ($cliente['nome'] == $nome) {
            return $cliente;
        }
    }
    return null;
}

// Requisito 3: Cadastro com validação (Corrigido o & e as chaves)
function cadastrarCliente(array &$lista, string $nome, string $cpf, string $email, float $contrato): bool {
    if (!$nome || !$cpf || !$email || $contrato <= 0) {
        echo "Dados inválidos! <br>";
        return false;
    }

    $lista[] = [
        "nome" => $nome,
        "cpf" => $cpf,
        "email" => $email,
        "contrato" => $contrato,
        "ativo" => true
    ];
    
    echo "Cliente $nome cadastrado!<br>";
    return true;
}

// Requisito 4: Limpeza de dados
function limparCPF(string $cpf): string {
    return str_replace(['.', '-'], '', $cpf);
}

function limparNome(string $nome): string {
    return trim($nome);
}

// Requisito 5: Formatação
function formatarNome(string $nome): string {
    return mb_convert_case(trim($nome), MB_CASE_TITLE, "UTF-8");
}

function formatarMoeda(float $valor): string {
    return "R$ " . number_format($valor, 2, ',', '.');
}

// Requisito 6: Resumo financeiro
function calcularTotalContratosAtivos(array $clientes): float {
    $total = 0.0;

    foreach ($clientes as $cliente) {
        if ($cliente['ativo']) {
            $total += $cliente['contrato'];
        }
    }
    return $total;
}

// Requisito 7: Alteração por referência
function aplicarReajuste(float &$contrato, float $percentual): void {
    $contrato += $contrato * ($percentual / 100);
}

// Requisito 8: Funções do Relatório Final (Corrigida a verificação no if)
function contarClientesAtivos(array $clientes): int {
    $ativos = 0;
    foreach ($clientes as $cliente) {
        if ($cliente['ativo']) {
            $ativos++;
        }
    }
    return $ativos;
}

function obterMaiorContrato(array $clientes): float {
    if (empty($clientes)) {
        return 0.0;
    }

    $maior = $clientes[0]['contrato'];

    foreach ($clientes as $cliente) {
        if ($cliente['contrato'] > $maior) {
            $maior = $cliente['contrato'];
        }
    }

    return $maior;
}

// Exibição do Relatório Final (Formatado)
echo "===== RELATÓRIO FINAL =====<br>";
echo "Total de clientes cadastrados: " . count($clientes) . "<br>";
echo "Total de clientes ativos: " . contarClientesAtivos($clientes) . "<br>";
echo "Maior contrato cadastrado: " . formatarMoeda(obterMaiorContrato($clientes)) . "<br>";
