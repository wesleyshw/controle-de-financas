<?php

class User
{

  private $db;
  private $error;

  function __construct($db_conn)
  {
    $this->db = $db_conn;

    session_start();
  }

  public function registrar($nome, $email, $senha, $conf)
  {
    if ($senha !== $conf) {
      $this->error = "Senhas são diferentes.";
      return false;
    };

    if (strlen($senha) < 6 or strlen($conf) < 6) {
      $this->error = "Senhas precisam ter no mínimo 6 caracteres.";
      return false;
    };
    try {
      $hash = password_hash($senha, PASSWORD_DEFAULT);

      $q = $this->db->prepare("INSERT INTO usuario (nome, email, senha) VALUES(?,?,?)");
      $q->execute(array($nome, $email, $hash));

      return true;
    } catch (PDOException $e) {
      if ($e->errorInfo[0] == 23000) {
        $this->error = "E-mail já está sendo utilizado!";
        return false;
      } else {
        echo $e->getMessage();
        return false;
      }
    }
  }

  public function logar($email, $senha)
  {
    try {

      $q = $this->db->prepare("SELECT * FROM usuario WHERE email = :e");
      $q->bindParam(":e", $email);
      $q->execute();

      $data = $q->fetch();

      if ($q->rowCount() > 0) {
        if (password_verify($senha, $data['senha'])) {
          $_SESSION['user'] = $data['id_usuario'];
          return true;
        } else {
          $this->error = "E-mail ou senha estão incorretos.";
          return false;
        }
      } else {
        $this->error = "E-mail ou senha estão incorretos.";
        return false;
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
      return false;
    }
  }

  public function logado()
  {
    if (isset($_SESSION['user'])) {
      return true;
    }
  }

  public function current_user()
  {
    if (!$this->logado()) {
      return false;
    }
    try {
      $q = $this->db->prepare("SELECT * FROM usuario WHERE id_usuario = :val");
      $q->bindParam(":val", $_SESSION['user']);
      $q->execute();

      return $q->fetch();
    } catch (PDOException $e) {
      echo $e->getMessage();
      return false;
    }
  }

  public function registrar_divida($id_user, $nome, $data_vencimento, $valor, $sit, $desc)
  {
    try {
      $q = $this->db->prepare("INSERT INTO dividas (id_user, nome, data_vencimento, valor, situacao, descricao) VALUES (?,?,?,?,?,?)");
      $q->execute(array($id_user, $nome, $data_vencimento, $valor, $sit, $desc));
      return true;
    } catch (PDOException $e) {
      echo $e->getMessage();
      return false;
    }
  }

  public function editar_divida($id, $id_user, $nome, $data_vencimento, $valor, $sit, $desc)
  {
    try {
      $query = $this->db->prepare("SELECT * FROM dividas WHERE id = :id");
      $query->bindValue(":id", $id);
      $query->execute();
      $data = $query->fetchAll();
      if ($query->rowCount() == 0) {
        $this->error = "Dívida não encontrada.";
        return false;
      } else {
        $data = $query->fetch();
      }

      $q = $this->db->prepare("UPDATE dividas SET id_user = :user, nome = :nome, data_vencimento = :data_venc, valor = :valor, situacao = :sit, descricao = :descr WHERE id = :div");
      $q->bindParam(":user", $id_user);
      $q->bindParam(":nome", $nome);
      $q->bindParam(":data_venc", $data_vencimento);
      $q->bindParam(":valor", $valor);
      $q->bindParam(":sit", $sit);
      $q->bindParam(":descr", $desc);
      $q->bindParam(":div", $id);
      $q->execute();
      return true;
    } catch (PDOException $e) {
      echo $e->getMessage();
      return false;
    }
  }

  public function logout()
  {
    session_destroy();
    unset($_SESSION['user']);
    return true;
  }

  public function getLastError()
  {
    return $this->error;
  }
}
