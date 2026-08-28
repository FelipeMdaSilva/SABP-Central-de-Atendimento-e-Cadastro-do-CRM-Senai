<?php
declare(strict_types=1);

require_once 'utilitarios.php';

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
//  processa o cadastro quando o formulário é enviado
$mensagemCadastro = '';
$sucesso = false;  
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novoNome = $_POST['nome'] ?? '';
    $novoCpf = $_POST['cpf'] ?? '';
    $novoEmail = $_POST['email'] ?? '';
    $novoContrato = (float) ($_POST['contrato'] ?? 0);

    $sucesso = cadastrarCliente($clientes, $novoNome, $novoCpf, $novoEmail, $novoContrato);
    $mensagemCadastro = $sucesso
        ? "Cliente \"$novoNome\" cadastrado com sucesso!"
        : "Dados inválidos. Verifique nome, CPF, e-mail e valor do contrato.";
}

// Busca via GET (?busca=nome)
$termoBusca = $_GET['busca'] ?? '';
$clienteEncontrado = $termoBusca !== '' ? buscarCliente($clientes, $termoBusca) : null;

?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central de Atendimento - CRM Senai </title>
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <style> 
        body {font-family: Arial, sans-serif; background: #ecf0f1; padding: 20px}
        .card {background-color: white; padding: 20px;border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); max-width: 700px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #3498db; color: white; }
        .ativo { color: green; font-weight: bold; }
        .inativo { color: #c0392b; font-weight: bold; }
        input, button { padding: 6px; margin: 4px 0; }
    </style>
</head>
 <body> 
   
    <!-- SEÇÃO 1: Listagem de Clientes  -->
    <h1>Central de Atendimento e Cadastro - CRM Senai</h1> 

    <div class = "card"> 
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
                        <td><?php echo formatarNome($cliente['nome']); ?></td>
                        <td><?php echo limparCPF($cliente['cpf']); ?></td>
                        <td><?php echo $cliente['email']; ?></td>
                        <td><?php echo formatarMoeda($cliente['contrato']); ?></td>
                        <td class="<?php echo $cliente['ativo'] ? 'ativo' : 'inativo'; ?>"><?php echo $cliente ['ativo'] ? 'Ativo' : 'Inativo'; ?>
                </td>
                    </tr>
                    <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- SEÇÃO 2: Busca por nome -->
   <div class="card">
    <h2>Buscar Cliente</h2>
    <form method="get" >
        <input type="text" name="busca" placeholder="Digite o nome do cliente" value="<?php echo htmlspecialchars($termoBusca); ?>">
        <button style="color: #3498db;" type="submit">Buscar</button>
    </form>
    <?php if ($termoBusca !== ''): ?>
        <?php if ($clienteEncontrado !== null): ?>
            <p><strong>Encontrado:</strong><?php echo formatarNome($clienteEncontrado['nome']); ?>
        - <?php echo formatarMoeda ($clienteEncontrado['contrato']); ?> </p>
        <?php else: ?>
            <p>Cliente não encontrado.</p>
            <?php endif; ?>
            <?php endif; ?>
            </div>
            <!-- SEÇÃO 3: Cadastro de novo cliente -->
             <div class="card">
                <h2>Cadastrar Novo Cliente</h2>

                <?php if ($mensagemCadastro !== ''): ?>
            <p class="<?php echo $sucesso ? 'sucesso' : 'erro'; ?>"><?php echo $mensagemCadastro; ?></p>
            <?php endif; ?>

            <form method="post"></form>
                <label>Nome: <input type="text" name="nome"></label><br>
                <label>CPF: <input type="text" name="cpf"></label><br>
                <label>E-mail: <input type="email" name="email"></label><br>
                <label>Valor do Contrato: <input type="text" name="contrato"></label><br>
                <button style="color: #3498db;" type="submit">Cadastrar</button>
        </form>
             </div>

             <!-- SEÇÃO 4: Relatório / Resumo financeiro -->
              <div class="card">
                <h2>Relatório</h2>
                <ul >
                   <li>Total de clientes: <?php echo count($clientes); ?></li>
            <li>Clientes ativos: <?php echo contarClientesAtivos($clientes); ?></li>
            <li>Total contratos ativos: <?php echo formatarMoeda(calcularTotalContratosAtivos($clientes)); ?></li>
            <li>Média dos contratos: <?php echo formatarMoeda(calcularMediaContratos($clientes)); ?></li>
            <li>Maior contrato: <?php echo formatarMoeda(obterMaiorContrato($clientes)); ?></li>
        </ul>
              </div>
</body>
</html>




