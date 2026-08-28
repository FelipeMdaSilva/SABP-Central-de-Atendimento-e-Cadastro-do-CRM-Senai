<?php
// utilitarios.php (versão temporária/stub - só pra visualizar o layout)
declare(strict_types=1);

function formatarNome(string $nome): string {
    return trim($nome);
}

function limparCPF(string $cpf): string {
    return $cpf;
}

function formatarMoeda(float $valor): string {
    return "R$ " . number_format($valor, 2, ',', '.');
}

function buscarCliente(array $clientes, string $nome): ?array {
    return null;
}

function contarClientesAtivos(array $clientes): int {
    return count(array_filter($clientes, fn($c) => $c['ativo']));
}

function calcularTotalContratosAtivos(array $clientes): float {
    $total = 0.0;
    foreach ($clientes as $c) {
        if ($c['ativo']) $total += $c['contrato'];
    }
    return $total;
}