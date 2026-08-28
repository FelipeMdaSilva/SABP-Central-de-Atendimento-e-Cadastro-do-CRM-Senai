<?php
declare(strict_types=1);


// Requisito 2: Busca por nome
function buscarCliente(array $clientes, string $nome): ?array {
    foreach ($clientes as $cliente) {
        if (mb_strtolower(trim($cliente['nome'])) === mb_strtolower(trim($nome))) {
            return $cliente;
        }
    }
    return null;
}

// Requisito 3: Cadastro com validação

function validarCPF(string $cpf): bool {
    $cpfLimpo = limparCPF($cpf);
    return strlen($cpfLimpo) === 11 && ctype_digit($cpfLimpo);
}

function validarEmail(string $email): bool {
    return str_contains($email, '@') && str_contains($email, '.');
}

function cadastrarCliente(array &$lista, string $nome, string $cpf, string $email, float $contrato): bool {
    if (!$nome || !$cpf || !$email || $contrato <= 0) {
        return false;
    }

    $lista[] = [
        "nome" => $nome,
        "cpf" => $cpf,
        "email" => $email,
        "contrato" => $contrato,
        "ativo" => true
    ];
    
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

// Requisito 8: Relatório Final 
function contarClientesAtivos(array $clientes): int {
    $ativos = 0;
    foreach ($clientes as $cliente) {
        if ($cliente['ativo']) {
            $ativos++;
        }
    }
    return $ativos;
}
//Média dos contratos 
function calcularMediaContratos(array $clientes): float {
    if (empty($clientes)) {
        return 0.0;
    }
    $soma = 0.0;
    foreach ($clientes as $cliente) {
        $soma += $cliente['contrato'];
    }
    return $soma / count($clientes);
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


