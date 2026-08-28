<?php
// testes.php
declare(strict_types=1);

require_once 'utilitarios.php';

$clientes = [
    ["nome" => " Ana Clara Silva ", "cpf" => "123.456.789-00", "email" => "ana@email.com", "contrato" => 1500.00, "ativo" => true],
    ["nome" => "Carlos Souza", "cpf" => "987.654.321-00", "email" => "carlos@email.com", "contrato" => 850.50, "ativo" => false],
];

echo "<pre>";

echo "formatarNome: " . formatarNome(" Ana Clara Silva ") . "\n";
echo "limparCPF: " . limparCPF("123.456.789-00") . "\n";

echo "buscarCliente (existente): " . (buscarCliente($clientes, "Ana Clara Silva") !== null ? "encontrado" : "não encontrado") . "\n";
echo "buscarCliente (inexistente): " . (buscarCliente($clientes, "Fulano") !== null ? "encontrado" : "não encontrado") . "\n";

echo "cadastro válido: " . (cadastrarCliente($clientes, "Novo", "111.222.333-44", "novo@email.com", 300) ? "sucesso" : "falhou") . "\n";
echo "cadastro nome vazio: " . (cadastrarCliente($clientes, "", "123.456.789-00", "a@a.com", 300) ? "sucesso" : "falhou") . "\n";
echo "cadastro CPF inválido: " . (cadastrarCliente($clientes, "X", "cpf-invalido", "a@a.com", 300) ? "sucesso" : "falhou") . "\n";
echo "cadastro contrato zero: " . (cadastrarCliente($clientes, "X", "123.456.789-00", "a@a.com", 0) ? "sucesso" : "falhou") . "\n";

echo "total contratos ativos: " . formatarMoeda(calcularTotalContratosAtivos($clientes)) . "\n";
echo "clientes ativos: " . contarClientesAtivos($clientes) . "\n";
echo "maior contrato: " . formatarMoeda(obterMaiorContrato($clientes)) . "\n";

$valor = 1000.00;
aplicarReajuste($valor, 10);
echo "reajuste 10% sobre 1000: " . formatarMoeda($valor) . "\n";

echo "</pre>";