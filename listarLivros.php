<html>
<head>

		<?php
        INCLUDE 'header.php';
		$complemento = "";
		if(isset($_POST["Pesquisar"]))
		{
		$filtro = $_POST["filtro"];
		$complemento = " where l.nomelivro LIKE '%$filtro%'";
		}		
		$sql = "select * from livro l".$complemento;
		
	
	 //executo no banco, retornando os registros da tabela
		$lista = mysqli_query($connection, $sql);
		
		//Exclui da Lista
		if(isset($_GET["id"]))
		{
			$id = $_GET["id"];
			$sqlSelectEmprestimo = "SELECT * FROM emprestimo WHERE idlivro= '$id'";
			$resultSet = mysqli_query($connection, $sqlSelectEmprestimo);
			$result = mysqli_fetch_array($resultSet);

			$idlivro = $result["idlivro"];

			if($idlivro <> "")
			{
				
				$erro = "<strong>Importante:</strong> Esse livro não pode ser excluído pois possuí vinculos de empréstimos.";
				$mensagem = "<div class=\"alert alert-danger\" role=\"alert\">".$erro."</div>";
			}
			else
			{
				$id = $_GET["id"];
				$sqlDelete = "DELETE FROM livro WHERE id=$id";
				mysqli_query($connection, $sqlDelete);
				$sucesso = "<strong>Muito Bom!</strong> Livro excluído com sucesso.";
				$mensagem = "<div class=\"alert alert-success\" role=\"alert\">".$sucesso."</div>";
				echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=listarLivros.php'>";
			}
		}

	 ?>
      
	
		<title>Livros</title>
		  </head>
		  <body>
		 <div class="container"> 
		<legend><span class="glyphicon glyphicon-book" aria-hidden="true"></span> Pesquisar livros</legend>
	  
		<form class="form-search" action="listarLivros.php" method="post">
	
		<div class="row">
 
  <div class="col-lg-6">
  <div class="form-group">
    <div class="input-group">
      <input type="text" class="form-control" placeholder="faça sua busca" type="text" name="filtro">
      <span class="input-group-btn">
        <button class="btn btn-default" type="submit" name="Pesquisar">Buscar</button>
      </span>
    </div><!-- /input-group -->
  </div><!-- /.col-lg-6 -->
</div>
</div>
		</form>
	
	 <?php if(isset($_GET["id"])){ echo $mensagem;} else{}?>
<div class="panel panel-primary">
  <!-- Default panel contents -->
  <div class="panel-heading">LISTA DE LIVROS</div>

	<div class="table-responsive">
  <table class="table table-bordered">
    <caption></caption>
    <thead>
	 <tbody>
    <tr>
    <th>Id</th>
    <th>Nome</th>
	<th>Acervo</th>
	<th>Disponíveis</th>
	<th>Emprestados</th>
	<th></th>
    </tr>
    </thead>
  <?php
   while($livro = mysqli_fetch_assoc($lista))
   {
   
   ?>
   
    <tr>
    <td><?php echo $livro ["id"]?></td>
    <td><?php echo $livro ["nomelivro"]?></td>
	<td><?php echo $livro ["quantidade"]?></td>
	<td><?php echo $livro ["quantidadedisponivel"]?></td>
	<td><?php echo $livro ["quantidadeemprestado"]?></td>
	
	   
<td>
	<a data-toggle='tooltip' data-placement='top' class="btn btn-danger" title="Excluir" href="listarLivros.php?id=<?php echo $livro["id"]?>" ><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
	<a data-toggle='tooltip' data-placement='top' class="btn btn-primary" title="Alterar" href="cadastrarLivro.php?id=<?php echo $livro["id"]?>"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td></tr>
  <?php 
   }
    ?>
  
    </tbody>
    </table></div></div></div>
	</body>
</html>