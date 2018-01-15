<html>
<head>

		<?php
	
        INCLUDE 'header.php';
		
		//Lista e Filtra busca
		$complemento = "";
		if(isset($_POST["filtro"]))
		{
			$filtro = $_POST["filtro"];
			$complemento = " where u.nomeusuario LIKE '%$filtro%' ";
		}		
		$sql = "select u.id, u.nomeusuario, u.login, u.senha, u.email from usuario u".$complemento;
		
		//executo no banco, retornando os registros da tabela
		$lista = mysqli_query($connection, $sql);
		
		//Exclui da Lista
		if(isset($_GET["id"]))
		{
			$id = $_GET["id"];
			$sqlSelectEmprestimo = "SELECT * FROM emprestimo WHERE idusuario= '$id'";
			$resultSet = mysqli_query($connection, $sqlSelectEmprestimo);
			$result = mysqli_fetch_array($resultSet);

			$idusuario = $result["idusuario"];

			if($idusuario <> "")
			{
				
				$erro = "<strong>Importante:</strong> Esse usuário não pode ser excluído pois possuí vinculos de empréstimos.";
				$mensagem = "<div class=\"alert alert-danger\" role=\"alert\">".$erro."</div>";
			}
			else
			{
				$id = $_GET["id"];
				$sqlDelete = "DELETE FROM usuario WHERE id=$id";
				mysqli_query($connection, $sqlDelete);
				$sucesso = "<strong>Muito Bom!</strong> Usuário excluído com sucesso.";
				$mensagem = "<div class=\"alert alert-success\" role=\"alert\">".$sucesso."</div>";
				echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=listarUsuarios.php'>";
			}
		}

	 ?>
      
	 
		  </head>
		  <body>
	 <div class="container"> 
		<legend><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Pesquisar usuários</legend>
	
		<form class="form-search" action="listarUsuarios.php" method="post">
		<div class="row">
 
  <div class="col-lg-6">
  <div class="form-group">
    <div class="input-group">
      <input type="text" class="form-control" placeholder="faça sua busca" type="text" name="filtro">
      <span class="input-group-btn">
        <button class="btn btn-default" type="submit" value="pesquisar">Buscar</button>
      </span>
    </div><!-- /input-group -->
  </div><!-- /.col-lg-6 -->
</div>
</div>
		</form>
	
		 <?php if(isset($_GET["id"])){ echo $mensagem;} else{}?>
<div class="panel panel-primary">
  <!-- Default panel contents -->
  <div class="panel-heading">LISTA DE USUÁRIOS</div>

		<div class="table-responsive">
  <table class="table table-bordered">
  
 
    <caption></caption>
    <thead>
	 <tbody>
    <tr>
    <th>Id</th>
    <th>Nome</th>
	<th>Login</th>
	<th>Email</th>
	<th> </th>
    </tr>
    </thead>
  <?php
  
   while($usuario = mysqli_fetch_assoc($lista))
   {
   
   ?>
   
    <tr>
    <td><?php echo $usuario ["id"]?></td>
    <td><?php echo $usuario ["nomeusuario"]?></td>
	<td><?php echo $usuario ["login"]?></td>
	<td><?php echo $usuario ["email"]?></td>
	   
	<td>
	<a data-toggle='tooltip' data-placement='top' class="btn btn-danger" title="Excluir" href="listarUsuarios.php?id=<?php echo $usuario["id"]?>" ><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
	<a data-toggle='tooltip' data-placement='top' class="btn btn-primary" title="Alterar" href="cadastrarUsuario.php?id=<?php echo $usuario["id"]?>"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td> </tr>
  <?php 
   }
    ?>
  
    </tbody>
    </table>
	</div></div></div>
	</body>
</html>