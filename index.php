<?php
declare(strict_types=1);

require_once 'utilitarios.php';

// Dados iniciais do projeto (2 originais + 2 clientes adicionados, conforme pedido no PDF)
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

$mensagemCadastro = '';
$sucessoCadastro = false;
$mensagemReajuste = '';
$sucessoReajuste = false;

// Todas as ações de POST desta tela chegam pelo mesmo formulário-base,
// diferenciadas pelo campo oculto "acao" (evita duplicar blocos de processamento).
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'cadastro') {
        $novoNome = $_POST['nome'] ?? '';
        $novoCpf = $_POST['cpf'] ?? '';
        $novoEmail = $_POST['email'] ?? '';
        $novoContrato = (float) ($_POST['contrato'] ?? 0);

        $sucessoCadastro = cadastrarCliente($clientes, $novoNome, $novoCpf, $novoEmail, $novoContrato);
        $mensagemCadastro = $sucessoCadastro
            ? "Cliente \"$novoNome\" cadastrado com sucesso!"
            : "Dados inválidos. Verifique nome, CPF, e-mail e valor do contrato.";
    } elseif ($acao === 'reajuste') {
        $cpfReajuste = $_POST['cpf_reajuste'] ?? '';
        $percentual = (float) ($_POST['percentual'] ?? 0);

        $sucessoReajuste = reajustarContratoCliente($clientes, $cpfReajuste, $percentual);
        $mensagemReajuste = $sucessoReajuste
            ? "Reajuste de " . number_format($percentual, 2, ',', '.') . "% aplicado com sucesso!"
            : "Não foi possível aplicar o reajuste. Verifique o CPF informado.";
    }
}

// Busca via GET (?busca=nome)
$termoBusca = $_GET['busca'] ?? '';
$clienteEncontrado = $termoBusca !== '' ? buscarCliente($clientes, $termoBusca) : null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central de Atendimento - CRM Senai</title>
    <style>
        body { font-family: Arial, sans-serif; background: #ecf0f1; padding: 20px; }
        .card { background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); max-width: 700px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #3498db; color: white; }
        .ativo { color: green; font-weight: bold; }
        .inativo { color: #c0392b; font-weight: bold; }
        input, button { padding: 8px; margin: 4px 0; }
        .sucesso { background: #e8f8f5; color: #117a65; padding: 10px; border-radius: 4px; margin-bottom: 10px; }
        .erro { background: #fdecea; color: #c0392b; padding: 10px; border-radius: 4px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1>Central de Atendimento e Cadastro - CRM Senai</h1>

    <!-- SEÇÃO 1: Listagem de Clientes -->
    <div class="card">
        <h2>Clientes Cadastrados</h2>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>E-mail</th>
                    <th>Contrato</th>
                    <th>Situação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?php echo htmlspecialchars(formatarNome($cliente['nome'])); ?></td>
                        <td><?php echo htmlspecialchars(limparCPF($cliente['cpf'])); ?></td>
                        <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                        <td><?php echo htmlspecialchars(formatarMoeda($cliente['contrato'])); ?></td>
                        <td class="<?php echo $cliente['ativo'] ? 'ativo' : 'inativo'; ?>">
                            <?php echo $cliente['ativo'] ? 'Ativo' : 'Inativo'; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p><em>Total de registros nesta listagem: <?php echo count($clientes); ?></em></p>
    </div>

    <!-- SEÇÃO 2: Busca por nome -->
    <div class="card">
        <h2>Buscar Cliente</h2>
        <form method="get">
            <input type="text" name="busca" placeholder="Digite o nome do cliente" value="<?php echo htmlspecialchars($termoBusca); ?>">
            <button style="color: #3498db;" type="submit">Buscar</button>
        </form>
        <?php if ($termoBusca !== ''): ?>
            <?php if ($clienteEncontrado !== null): ?>
                <p><strong>Encontrado:</strong> <?php echo htmlspecialchars(formatarNome($clienteEncontrado['nome'])); ?>
                - <?php echo htmlspecialchars(formatarMoeda($clienteEncontrado['contrato'])); ?></p>
            <?php else: ?>
                <p>Cliente não encontrado.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- SEÇÃO 3: Cadastro de novo cliente -->
    <div class="card">
        <h2>Cadastrar Novo Cliente</h2>

        <?php if ($mensagemCadastro !== ''): ?>
            <p class="<?php echo $sucessoCadastro ? 'sucesso' : 'erro'; ?>"><?php echo htmlspecialchars($mensagemCadastro); ?></p>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="acao" value="cadastro">
            <label>Nome: <input type="text" name="nome" required></label><br>
            <label>CPF: <input type="text" name="cpf" placeholder="000.000.000-00" required></label><br>
            <label>E-mail: <input type="email" name="email" required></label><br>
            <label>Valor do Contrato: <input type="number" step="0.01" min="0.01" name="contrato" required></label><br>
            <button style="color: #3498db;" type="submit">Cadastrar</button>
        </form>
    </div>

    <!-- SEÇÃO 4: Reajuste de contrato (passagem por referência) -->
    <div class="card">
        <h2>Aplicar Reajuste em Contrato</h2>
        <p><em>Informe o CPF de um cliente já cadastrado e o percentual de reajuste (pode ser negativo para desconto).</em></p>

        <?php if ($mensagemReajuste !== ''): ?>
            <p class="<?php echo $sucessoReajuste ? 'sucesso' : 'erro'; ?>"><?php echo htmlspecialchars($mensagemReajuste); ?></p>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="acao" value="reajuste">
            <label>CPF do cliente: <input type="text" name="cpf_reajuste" placeholder="000.000.000-00" required></label><br>
            <label>Percentual (%): <input type="number" step="0.01" name="percentual" required></label><br>
            <button style="color: #3498db;" type="submit">Aplicar Reajuste</button>
        </form>
    </div>

    <!-- SEÇÃO 5: Relatório / Resumo financeiro -->
    <div class="card">
        <h2>Relatório</h2>
        <ul>
            <li>Total de clientes: <?php echo count($clientes); ?></li>
            <li>Clientes ativos: <?php echo contarClientesAtivos($clientes); ?></li>
            <li>Total de contratos ativos: <?php echo htmlspecialchars(formatarMoeda(calcularTotalContratosAtivos($clientes))); ?></li>
            <li>Média dos contratos: <?php echo htmlspecialchars(formatarMoeda(calcularMediaContratos($clientes))); ?></li>
            <li>Maior contrato: <?php echo htmlspecialchars(formatarMoeda(obterMaiorContrato($clientes))); ?></li>
        </ul>
    </div>
</body>
</html>
