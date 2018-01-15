<html>
<head>

		<?php
        INCLUDE 'headerUsuarioComum.php';
		
		$complemento = "";
		if(isset($_POST["filtro"]))
		{
			$filtro = $_POST["filtro"];
			$complemento = " and u.nomeusuario LIKE '%$filtro%' or l.nomelivro LIKE '%$filtro%'";
		}		
			$login_cookie = $_COOKIE['login'];
	
		$sql = "select e.id, e.status, e.idusuario, u.nomeusuario, u.email, u.login, e.renovacao, e.datarenovacao1, e.datarenovacao2, l.nomelivro, e.dataemprestimo, e.datadevolucao, e.dataprevista from emprestimo e
			INNER JOIN livro l ON (e.idlivro = l.id)
			INNER JOIN usuario u ON (e.idusuario = u.id) 
			where u.login like '%$login_cookie%'
			".$complemento."group by e.id order by e.id desc";
  
  
		$lista = mysqli_query($connection, $sql);
		

		if(isset($_GET["id"]))
		{
			$id = $_GET["id"];
				$resultado1= mysqli_query($connection,"SELECT * FROM emprestimo WHERE id = $id and renovacao = 0");
				$resultado2= mysqli_query($connection,"SELECT * FROM emprestimo WHERE id = $id and renovacao = 1");
		
				
				$select= mysqli_query($connection,"SELECT * FROM emprestimo WHERE id = $id");
					$row = mysqli_fetch_row($select);
					$dataprevista = $row[6];
					$dias = 7;
			if(mysqli_num_rows($resultado1) == 1)
			{
					
				$novadataprevista = date('y/m/d', strtotime('+'.$dias.'days', strtotime($dataprevista)));

				$renovacao = 1;
				$sqlRenovar = "UPDATE emprestimo set dataprevista = '$novadataprevista', datarenovacao1 = now(), renovacao = '$renovacao' WHERE id='$id'";
				mysqli_query($connection, $sqlRenovar);
				$sucesso = "<strong>Muito Bom!</strong> Empréstimo de livro renovado por mais 7 dias.";
				$mensagem = "<div class=\"alert alert-success\" role=\"alert\">".$sucesso."</div>";
				echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=listarEmprestimosPorUsuario.php'>";
				echo $dateHoje;
			}
			if(mysqli_num_rows($resultado2) == 1)
			{
			
				$novadataprevista = date('y/m/d', strtotime('+'.$dias.'days', strtotime($dataprevista)));
					$dateHoje = date('Y/m-d');
				$dataatual = strtotime($dataHoje);
				$renovacao = 2;
				$sqlRenovar = "UPDATE emprestimo set dataprevista = '$novadataprevista', datarenovacao2 = now(), renovacao = '$renovacao' WHERE id='$id'";
				mysqli_query($connection, $sqlRenovar);
				$sucesso = "<strong>Muito Bom!</strong> Empréstimo de livro renovado por mais 7 dias.";
				$mensagem = "<div class=\"alert alert-success\" role=\"alert\">".$sucesso."</div>";
				echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=listarEmprestimosPorUsuario.php'>";
			}
		}		 

	 ?>
      
	
		<title>Empréstimos</title>
		  </head>
		  <body>
		 <div class="container"> 
		<legend><span class="glyphicon glyphicon-transfer" aria-hidden="true"></span> Pesquisar Empréstimos</legend>
		 <?php if(isset($_GET['id']) == 1){echo $mensagem;} else{}?>
		<div class="alert alert-warning" role="alert"><strong>Atenção!</strong></br> 1- Você tem direito a renovar cada empréstimo 2 vezes.</br>2- Empréstimos atrasados não podem ser renovados.</div>
		<form class="form-search" action="listarEmprestimosPorUsuario.php" method="post">
	
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
	<div class="panel panel-primary">
  <!-- Default panel contents -->
  <div class="panel-heading">LISTA DE EMPRÉSTIMOS</div>

		
	<div class="table-responsive">
  <table class="table table-bordered">
    <caption></caption>
     <thead>
	 <tbody>
    <tr>
	<th>Status</th>

    <th>Livro</th>
	<th>Data Empréstimo</th>
	<th>Data Prevista Dev.</th>
	<th>Renovado</th>
	<th>Data 1ªRenovação</th>
	<th>Data 2ªRenovação</th>
	<th>Data Devolução</th>
	 <th></th>

    </tr>
    </thead>
  <?php
  
   while($emprestimoUsuario = mysqli_fetch_assoc($lista))
   {
   
   ?>
 
		<?php 
	$dateHoje = date('Y-m-d');
	if((strtotime($dateHoje) > strtotime($emprestimoUsuario["dataprevista"])) && $emprestimoUsuario["status"] == 0)
		echo "<tr class=\"danger\"><td><span data-toggle='tooltip' data-placement='top' title='Empréstimo atrasado.' style='color:#a94442' class='glyphicon glyphicon-alert' aria-hidden='true'></span></td>";
	else if($emprestimoUsuario["status"] == 1)
		echo "<tr class=\"success\"><td><span data-toggle='tooltip' data-placement='top' title='Empréstimo Devolvido.' style='color:#4cae4c' class='glyphicon glyphicon-ok-sign' aria-hidden='true'></span></td>";
	else
		echo "<tr><td><span data-toggle='tooltip' data-placement='top' title='Em andamento.' style='color:' class='glyphicon glyphicon-export' aria-hidden='true'></span></td>";
	
	?>
	
    <td value="<?php echo $emprestimoUsuario["idlivro"]?> "><?php echo $emprestimoUsuario ["nomelivro"]?></td>
	 <td><?php echo date('d/m/Y', strtotime($emprestimoUsuario ["dataemprestimo"]))?></td>
	   <td><?php echo date('d/m/Y', strtotime($emprestimoUsuario ["dataprevista"]))?></td>
	    <td value="<?php echo $emprestimoUsuario["renovacao"]?> "><?php echo $emprestimoUsuario ["renovacao"]?> vez(es)</td>
		 <td><?php if($emprestimoUsuario ["datarenovacao1"] == '0000-00-00'){echo "-";}else{echo date('d/m/Y', strtotime($emprestimoUsuario ["datarenovacao1"]));}?></td>
		 <td><?php if($emprestimoUsuario ["datarenovacao2"] == '0000-00-00'){echo "-";}else{echo date('d/m/Y', strtotime($emprestimoUsuario ["datarenovacao2"]));}?></td>
	  <td><?php if($emprestimoUsuario ["datadevolucao"] == '0000-00-00'){echo "-";}else{echo date('d/m/Y', strtotime($emprestimoUsuario ["datadevolucao"]));}?></td>

	<td>
	<?php 
	if((strtotime($dateHoje) < strtotime($emprestimoUsuario["dataprevista"])) && $emprestimoUsuario["status"] == 0 && $emprestimoUsuario["renovacao"] < 2)
	{
	echo "<a data-toggle='tooltip' data-placement='top' class='btn btn-success' title='Renovar Empréstimo' href='listarEmprestimosPorUsuario.php?id=".$emprestimoUsuario["id"]."'><span class='glyphicon glyphicon-refresh' aria-hidden='true'></span></a>";
   }
   else
   {}
?></td>
  <?php 
   }
    ?>
  
    </tbody>
    </table></div></div></div>

	</body>
</html>