<?php
declare(strict_types=1);
/** Remove espaços desnecessários no início/fim do nome. */
function limparNome(string $nome): string
{
    return trim($nome);
}
/** Remove pontuação do CPF (ponto, traço e espaço), deixando só números. */
function limparCPF(string $cpf): string
{
    // uso explícito de str_replace, conforme requisito técnico
    return str_replace(['.', '-', ' '], '', trim($cpf));
}

/** Verifica se o CPF (já limpo) tem 11 dígitos numéricos. */
function validarCPF(string $cpf): bool
{
    $cpfLimpo = limparCPF($cpf);

    if (strlen($cpfLimpo) !== 11) {
        return false;
    } elseif (!ctype_digit($cpfLimpo)) {
        return false;
    } else {
        return true;
    }
}

/** Verifica se o e-mail tem um formato válido. */
function validarEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}
/**
 * Cadastra um novo cliente na lista, validando todos os campos.
 * Retorna true se o cadastro foi realizado, false se os dados forem inválidos.
 */
function cadastrarCliente(array &$clientes, string $nome, string $cpf, string $email, float $contrato): bool
{
    $nomeLimpo = limparNome($nome);

    if ($nomeLimpo === '') {
        return false;
    } elseif (!validarCPF($cpf)) {
        return false;
    } elseif (!validarEmail($email)) {
        return false;
    } elseif ($contrato <= 0) {
        return false;
    }

    $clientes[] = [
        "nome"     => formatarNome($nomeLimpo),
        "cpf"      => limparCPF($cpf),
        "email"    => $email,
        "contrato" => $contrato,
        "ativo"    => true,
    ];

    return true;
}

/** Busca um cliente pelo nome (busca parcial, sem diferenciar maiúsculas/minúsculas). */
function buscarCliente(array $clientes, string $nome): ?array
{
    $termoLimpo = strtolower(trim($nome));

    if ($termoLimpo === '') {
        return null;
    }

    foreach ($clientes as $cliente) {
        $nomeCliente = strtolower(trim($cliente['nome']));

        if (str_contains($nomeCliente, $termoLimpo)) {
            return $cliente;
        }
    }

    return null;
}

/** Padroniza o nome em formato "Título" (primeira letra de cada palavra maiúscula). */
function formatarNome(string $nome): string
{
    $nomeLimpo = trim($nome);
    return ucwords(strtolower($nomeLimpo));
}

/** Formata um valor float no padrão de moeda brasileira. */
function formatarMoeda(float $valor): string
{
    return "R$ " . number_format($valor, 2, ',', '.');
}
/** Soma o valor dos contratos apenas dos clientes ativos. */
function calcularTotalContratosAtivos(array $clientes): float
{
    $total = 0.0;

    foreach ($clientes as $cliente) {
        if ($cliente['ativo']) {
            $total += $cliente['contrato'];
        }
    }

    return $total;
}

/** Calcula a média dos valores de contrato de todos os clientes. */
function calcularMediaContratos(array $clientes): float
{
    if (count($clientes) === 0) {
        return 0.0;
    }

    $soma = 0.0;
    foreach ($clientes as $cliente) {
        $soma += $cliente['contrato'];
    }

    return $soma / count($clientes);
}

/** Retorna o maior valor de contrato entre todos os clientes. */
function obterMaiorContrato(array $clientes): float
{
    if (count($clientes) === 0) {
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

/** Conta quantos clientes estão marcados como ativos. */
function contarClientesAtivos(array $clientes): int
{
    $ativos = 0;

    foreach ($clientes as $cliente) {
        if ($cliente['ativo']) {
            $ativos++;
        }
    }

    return $ativos;
}

//  * Aplica um reajuste percentual sobre um valor de contrato,
//  * alterando diretamente a variável original (passagem por referência).
function aplicarReajuste(float &$contrato, float $percentual): void
{
    $contrato += $contrato * ($percentual / 100);
}
/**
 * Localiza um cliente pelo CPF dentro da lista e aplica o reajuste
 */
function reajustarContratoCliente(array &$clientes, string $cpfBusca, float $percentual): bool
{
    $cpfLimpo = limparCPF($cpfBusca);

    foreach ($clientes as &$cliente) {
        if (limparCPF($cliente['cpf']) === $cpfLimpo) {
            aplicarReajuste($cliente['contrato'], $percentual);
            unset($cliente);
            return true;
        }
    }
    unset($cliente);

    return false;
}