<?php

require_once '../banco.php';
require_once '../usuario.php';

$pdo = Banco::conectar();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$user = new User($pdo);

if ($user->logado()) {
  header("location: /finances/inicio/");
};

if (isset($_POST['register'])) {
  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $senha = $_POST['senha'];
  $conf = $_POST['conf'];
  if ($user->registrar($nome, $email, $senha, $conf)) {
    Banco::desconectar();
    $success = true;
  } else {
    Banco::desconectar();
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
  <title>Controle de finanças - Registrar</title>
  <link rel="shortcut icon" href="../assets/imgs/financa-white.png" />
  <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../assets/css/registro/style.css">
  <link href="../assets/css/font-awesome/font-awesome.min.css" rel="stylesheet">
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-5 mx-auto">
        <div id="first">
          <div class="myform form">
            <form method="post" name="registration">
              <div class="logo mt-5 mb-4">
                <div class="col-md-12 text-center">
                <img src="../assets/imgs/financa-white.png" alt="" width="100" height="90" class="d-inline-block align-text-top mb-3">
                  <h1 class="cd-tl">Controle de finanças</h1>
                </div>
              </div>
              <?php if (isset($error)) : ?>
                <div class="alert alert-danger">
                  <?php echo $error ?>
                </div>
              <?php endif; ?>
              <?php if (isset($success)) : ?>
                <div class="alert alert-success">
                  Registro concluído com sucesso. <a href="http://localhost/finances/">clique aqui para fazer o login.</a>
                </div>
              <?php endif; ?>
              <div class="form-group mb-3" id="name">
                <input type="text" name="nome" class="form-control" id="nome" placeholder="Nome do comércio" required>
              </div>
              <div class="form-group mb-3" id="email">
                <input type="email" name="email" class="form-control" id="email" placeholder="E-mail" required>
              </div>
              <div class="form-group mb-3" id="password">
                <input type="password" name="senha" id="senha" class="form-control" placeholder="Senha" required>
              </div>
              <div class="form-group mb-5" id="conf">
                <input type="password" name="conf" id="conf" class="form-control" placeholder="Confirmar Senha" required>
              </div>
              <div class="col-md-12 text-center">
                <button type="submit" name="register" class="btn btn-block mybtn btn-primary">Registrar</button>
              </div>
              <div class="col-md-12 mb-5">
                <div class="form-group">
                  <p class="text-center"><a href="http://localhost/finances/" class="register inp-sub-2">Já possui uma conta.</a></p>
                </div>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="../assets/js/jquery/jquery.min.js"></script>
  <script src="../assets/js/jquery/jquery.validate.min.js"></script>
  <script src="../assets/js/registro/style.js"></script>
</body>

</html>