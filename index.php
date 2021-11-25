<?php

session_start();

require_once 'banco.php';
require_once 'usuario.php';

$pdo = Banco::conectar();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$user = new User($pdo);

if ($user->logado()) {
  header("location: /finances/inicio/");
};

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $senha = $_POST['senha'];
  if ($user->logar($email, $senha)) {
    Banco::desconectar();
    header("location: /finances/inicio/");
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
  <title>Controle de finanças - Entrar</title>
  <link rel="shortcut icon" href="./assets/imgs/financa-white.png" />
  <link href="./assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <script src="./assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="./assets/css/entrar/style.css">
  <link href="./assets/css/font-awesome/font-awesome.min.css" rel="stylesheet">
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-5 mx-auto">
        <div id="first">
          <div class="myform form">
            <form method="post" name="login">
              <div class="logo mt-5 mb-5">
                <div class="col-md-12 text-center">
                <img src="./assets/imgs/financa-white.png" alt="" width="60" height="44" class="d-inline-block align-text-top">
                  <h1 class="cd-tl">Controle de finanças</h1>
                </div>
              </div>
              <?php if (isset($error)) : ?>
                <div class="alert alert-danger">
                  <?php echo $error ?>
                </div>
              <?php endif; ?>
              <div class="form-group mb-5" id="email">
                <input type="email" name="email" class="form-control" id="email" placeholder="E-mail">
              </div>
              <div class="form-group" id="password">
                <input type="password" name="senha" id="senha" class="form-control" placeholder="Senha">
              </div>
              <div class="form-group mb-5">
                <a href="#" class="inp-sub" id="forgot">Esqueceu a senha?</a></p>
              </div>

              <div class="col-md-12 text-center">
                <button type="submit" name="login" class="btn btn-block mybtn btn-primary">Logar</button>
              </div>
              <div class="col-md-12 mb-5">
                <div class="form-group">
                  <p class="text-center"><a href="http://localhost/finances/registro/" class="register inp-sub-2">Não possui uma conta? clique aqui.</a></p>
                </div>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="./assets/js/jquery/jquery.min.js"></script>
  <script src="./assets/js/jquery/jquery.validate.min.js"></script>
  <script src="./assets/js/entrar/style.js"></script>
</body>

</html>