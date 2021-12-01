<?php

require_once '../banco.php';
require_once '../usuario.php';

$pdo = Banco::conectar();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$user = new User($pdo);

if (!$user->logado()) {
  header("location: /finances/");
}

$currentUser = $user->current_user();

$query = $pdo->prepare("SELECT * FROM dividas WHERE id_user = :id");
$query->bindValue(":id", $_SESSION["user"]);
$query->execute();
$data = $query->fetchAll();

if (isset($_POST['add_divida'])) {

  $nome = $_POST['nome'];
  $data_vencimento = $_POST['data-vencimento'];
  $valor = $_POST['valor'];
  $sit = $_POST['situacao'];
  $desc = $_POST['descricao'];

  if ($user->registrar_divida($currentUser['id'], $nome, $data_vencimento, $valor, $sit, $desc)) {
    // Banco::desconectar();
    header("Refresh:0");
  } else {
    // Banco::desconectar();
    $error = $user->getLastError();
  }
};

if (isset($_POST['testee'])) {

  $nome = $_POST['nome'];
  $data_vencimento = $_POST['data-vencimento'];
  $valor = $_POST['valor'];
  $sit = $_POST['situacao'];
  $desc = $_POST['descricao'];
  $id = $_POST['dividaA'];

  if ($user->editar_divida($id, $currentUser['id'], $nome, $data_vencimento, $valor, $sit, $desc)) {
    // Banco::desconectar();
    header("Refresh:0");
  } else {
    // Banco::desconectar();
    $error = $user->getLastError();
  }
};

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Controle de finanças - Inicio</title>
  <link rel="shortcut icon" href="../assets/imgs/financa-white.png" />
  <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../assets/css/inicio/style.css">
  <link href="../assets/css/font-awesome/font-awesome.min.css" rel="stylesheet">
</head>

<body>

  <nav class="navbar navbar-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="../assets/imgs/financa-white.png" alt="" width="30" height="24" class="d-inline-block align-text-top">
        Controle de finanças
      </a>
    </div>
  </nav>

  <br>

  <!-- <div>Ícones feitos por <a href="https://www.freepik.com" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/br/" title="Flaticon">www.flaticon.com</a></div> -->
  <div class="container">
    <div class="row">

      <?php if (isset($error)) : ?>
        <div class="alert alert-danger">
          <?php echo $error ?>
        </div>
      <?php endif; ?>

      <a class="btn mb-3 add_div" data-bs-toggle="modal" data-bs-target="#add">
        Adicionar
      </a>

      <br>

      <div class="modal fade" id="add" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Adicionar dívida</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form method="post" class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Nome completo:</label>
                  <input type="text" class="form-control" name="nome">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Data de vencimento:</label>
                  <input type="date" class="form-control" name="data-vencimento">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Valor:</label>
                  <input type="text" class="form-control" name="valor">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Situação:</label>
                  <select name="situacao" class="form-select">
                    <option selected>Pendente</option>
                    <option>Recebido</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Descrição</label>
                  <textarea class="form-control" name="descricao" rows="3"></textarea>
                </div>
                <div class="col-12">
                  <button class="btn btn-success" type="submit" name="add_divida">Salvar</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <table class="table table-hover tdd">
        <thead>
          <tr class="td">
            <th class="bdr" scope="col">Nome</th>
            <th class="bdr" scope="col">Data de vencimento</th>
            <th class="bdr" scope="col">Valor</th>
            <th class="bdr" scope="col">Situação</th>
            <th scope="col">Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data as $value) : ?>
            <tr>
              <th class="bdr"><?php echo $value['nome'] ?></th>
              <th class="bdr"><?php echo date("d/m/Y", strtotime($value['data_vencimento'])) ?></th>
              <th class="bdr"><?php echo $value['valor'] ?></th>
              <th class="bdr"><span class="<?php if ($value['situacao'] == "Pendente") { echo "pendente"; } else if ($value['situacao'] == "Recebido") { echo "recebido"; }; ?>"><?php echo $value['situacao'] ?></span></th>
              <th>
                <a data-bs-toggle="modal" data-bs-target="#view_<?php echo $value['id'] ?>"><button type="button" class="btn btn-primary btt">Visualisar</button></a>
                <a data-bs-toggle="modal" data-bs-target="#edit_<?php echo $value['id'] ?>"><button type="button" class="btn btn-success btt">Editar</button></a>
                <a href="/finances/inicio/remove.php?id=<?php echo $value['id'] ?>" onclick="return confirm('Você realmente deseja remover essa dívida?'); "><button type="button" class="btn btn-danger btt">Remover</button></a>
              </th>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php foreach ($data as $value) : ?>
        <div class="modal fade" id="edit_<?php echo $value['id'] ?>" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Editar dívida</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form method="post" class="row g-3">
                  <input type="hidden" id="hidden" name="dividaA" value="<?php echo $value['id'] ?>">
                  <div class="col-md-6">
                    <label class="form-label">Nome completo:</label>
                    <input type="text" class="form-control" name="nome" value="<?php echo $value['nome'] ?>">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Data de vencimento:</label>
                    <input type="date" class="form-control" name="data-vencimento" value="<?php echo $value['data_vencimento'] ?>">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Valor:</label>
                    <input type="text" class="form-control" name="valor" value="<?php echo $value['valor'] ?>">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Situação:</label>
                    <select name="situacao" class="form-select">
                      <option value="<?php echo $value['situacao'] ?>"><?php echo $value['situacao'] ?></option>
                      <option disabled>--------------</option>
                      <option selected>Pendente</option>
                      <option>Recebido</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Descrição</label>
                    <textarea class="form-control" name="descricao" rows="3"><?php echo $value['descricao'] ?></textarea>
                  </div>
                  <div class="col-12">
                    <button class="btn btn-success" type="submit" name="testee">Salvar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="view_<?php echo $value['id'] ?>" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Visualisar dívida</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label">Nome completo:</label>
                    <input type="text" class="form-control" name="nome" value="<?php echo $value['nome'] ?>" disabled>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Data de vencimento:</label>
                    <input type="date" class="form-control" name="data-vencimento" value="<?php echo $value['data_vencimento'] ?>" disabled>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Valor:</label>
                    <input type="text" class="form-control" name="valor" value="<?php echo $value['valor'] ?>" disabled>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Situação:</label>
                    <select name="situacao" class="form-select" disabled>
                      <option><?php echo $value['situacao'] ?></option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Descrição</label>
                    <textarea class="form-control" name="descricao" rows="3" disabled><?php echo $value['descricao'] ?></textarea>
                  </div>
                  
                </form>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <script src="../assets/js/jquery/jquery.min.js"></script>
  <script src="../assets/js/jquery/jquery.validate.min.js"></script>
  <script src="../assets/js/inicio/style.js"></script>
</body>

</html>