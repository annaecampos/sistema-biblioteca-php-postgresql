<html>
   <head>
      <?php
         INCLUDE "header.php";
		 //Lista e Filtra busca
		$complemento = "";
		if(isset($_POST["filtro"]))
		{
			$filtro = $_POST["filtro"];
			$complemento = " and u.nomeusuario LIKE '%$filtro%' or l.nomelivro LIKE '%$filtro%'";
		}		
		
		  $sql = "select e.id, u.nomeusuario, l.nomelivro, e.dataemprestimo, e.datadevolucao, e.dataprevista from usuario u, livro l, emprestimo e where u.id = e.idusuario and l.id = e.idlivro and e.status = 0".$complemento;

					
		$lista = mysqli_query($connection, $sql);
	
          if(isset($_GET["id"]))
         {
		
			$id = $_GET["id"];
			$status = 1;
			 
			$resultado= mysqli_query($connection,"SELECT * FROM emprestimo WHERE id = $id");
			if (mysqli_num_rows($resultado) > 0) 
			{
         
				 $sql = "UPDATE emprestimo SET  
				status = '$status',
				datadevolucao = now()
				WHERE id = '$id'";
				mysqli_query($connection, $sql);
				$selec= mysqli_query($connection,"SELECT * FROM emprestimo 
					INNER JOIN livro ON (emprestimo.idlivro = livro.id) 
					WHERE emprestimo.id = $id");
					$row = mysqli_fetch_row($selec);
			
					$idlivro = $row[1];
					$selec2= mysqli_query($connection,"SELECT * FROM livro WHERE id = $idlivro");
					$row2 = mysqli_fetch_row($selec2);
					$quantidadedisponivel = $row2[3];
					$quantidadeemprestado = $row2[4];
			
						$qtddisp = $quantidadedisponivel + 1;
						$qtdemprest = $quantidadeemprestado - 1;
						$sql = "UPDATE livro SET  
						quantidadedisponivel = '$qtddisp',
						quantidadeemprestado = '$qtdemprest'
						WHERE id = '$idlivro'";
						mysqli_query($connection, $sql);
        
				$sucesso = "<strong>Muito Bom!</strong> O livro foi devolvido com sucesso.";
				$mensagem = "<div class=\"alert alert-success\" role=\"alert\">".$sucesso."</div>";	
			
				echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=cadastrarDevolucao.php'>";
					
				
			}
			
		 }
		 
		 if(isset($_POST["Carregar"]))
         {
			$idusuario = $_POST["idusuario"];
			 
			$resultadoCarregar= mysqli_query($connection, "select * from emprestimo e where e.idusuario = '$idusuario' and e.status = 0");
	
		 }
		 
		 
         
 ?>
   </head>
   <body>
      <div class="container">
	 
            <legend><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Cadastro de Devolução</legend>
			
            <form class="form-search" action="cadastrarDevolucao.php" method="post">
			    <?php if(isset($_POST["Alterar"]) || isset($_POST["Inserir"])){ echo $mensagem;} else{}?>
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
</div></div>
		</form>
		 
		 <div class="panel panel-primary">
  <!-- Default panel contents -->
  <div class="panel-heading">LISTA DE EMPRÉSTIMOS <strong>NÃO DEVOLVIDOS</strong></div>

		<div class="table-responsive">
  <table class="table table-bordered">
    <caption></caption>
    <thead>
	 <tbody>
  <tr>
  	<th>Status</th>
	<th>Id</th>
    <th>Usuário</th>
    <th>Livro</th>
	<th>Data Empréstimo</th>
	<th>Data Prevista Para Dev.</th>
	 <th></th>

    </tr>
    </thead>
  <?php
   while($emprestimo = mysqli_fetch_assoc($lista))
   {
   
   ?>
   <?php 
	$dateHoje = date('Y-m-d');
	if((strtotime($dateHoje) > strtotime($emprestimo["dataprevista"])))
		echo "<tr class=\"danger\"><td><span data-toggle='tooltip' data-placement='top' title='Empréstimo atrasado.' style='color:#a94442' class='glyphicon glyphicon-alert' aria-hidden='true'></span></td>";
	else
		echo "<tr><td><span data-toggle='tooltip' data-placement='top' title='Em andamento.' style='color:' class='glyphicon glyphicon-export' aria-hidden='true'></span></td>";
	
	?>
	<td value="<?php echo $emprestimo["id"]?>"><?php echo $emprestimo ["id"]?></td>
    <td value="<?php echo $emprestimo["idusuario"]?>"><?php echo $emprestimo ["nomeusuario"]?></td>
    <td value="<?php echo $emprestimo["idlivro"]?> "><?php echo $emprestimo ["nomelivro"]?></td>
	 <td><?php echo date('d/m/Y', strtotime($emprestimo ["dataemprestimo"]))?></td>
	 <td><?php echo date('d/m/Y', strtotime($emprestimo ["dataprevista"]))?></td>

	   
	<td><a data-toggle='tooltip' data-placement='top' class="btn btn-success" title="Devolver" href="cadastrarDevolucao.php?id=<?php echo $emprestimo["id"]?>"><span class="glyphicon glyphicon-transfer" aria-hidden="true"></span></a></td> </tr>
	
  <?php 
   }
    ?>
  
    </tbody>
    </table>
  </div>
      </div> 
	<script>
</script>

   </body>
   
   
</html>