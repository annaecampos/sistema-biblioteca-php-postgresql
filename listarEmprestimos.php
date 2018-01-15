<html>
<head>

		<?php
        INCLUDE 'header.php';
		
		$complemento = "";
		if(isset($_POST["filtro"]))
		{
			$filtro = $_POST["filtro"];
			$complemento = " where u.nomeusuario LIKE '%$filtro%' or l.nomelivro LIKE '%$filtro%'";
		}		
		$sql = "select e.id, e.status, e.idusuario, u.nomeusuario, u.email, l.nomelivro, e.dataemprestimo, e.datadevolucao, e.renovacao, e.datarenovacao1, e.datarenovacao2, e.dataprevista from emprestimo e
			INNER JOIN livro l ON (e.idlivro = l.id)
			INNER JOIN usuario u ON (e.idusuario = u.id) 
			".$complemento."group by e.id order by e.id desc";
  
  
		$lista = mysqli_query($connection, $sql);
		


		if(isset($_GET['id']) == 1)
		{
			$sqlEnviar = "select e.id, e.status, e.idusuario, u.nomeusuario, u.email, l.nomelivro, e.dataemprestimo, e.datadevolucao, e.dataprevista from emprestimo e
			INNER JOIN livro l ON (e.idlivro = l.id)
			INNER JOIN usuario u ON (e.idusuario = u.id) 
			where status = 0 and datediff(now(), dataemprestimo) > 7";
			$result = mysqli_query($connection, $sqlEnviar);
			if (!$result) 
			{
				echo "Ocorreu um erro.\n";
				exit;
			}
			while ($resultado = mysqli_fetch_array($result)) 
			{ 
				$nomeusuario = $resultado["nomeusuario"];
				$email = $resultado["email"];
				$dataemprestimo = date('d/m/Y', strtotime($resultado["dataemprestimo"]));
				$dataprevista = date('d/m/Y', strtotime($resultado["dataprevista"]));
				$nomelivro = $resultado["nomelivro"];
				$nomearquivo = $nomeusuario;
				
				include_once('fpdf16/fpdf.php');
				include("relatorioFPDF.php");
				
				//include_once("mpdf60/mpdf.php");
				//include("relatorioMPDF.php");
				
			//include_once('TCPDF/tcpdf.php');
			//	include("relatorioTCPDF.php");
			
			ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
				include_once("phpmailer99/class.phpmailer.php"); 
				include_once("phpmailer99/class.smtp.php");	
			
				include("envio2.php");
			}
			$sucesso = "<strong>Muito Bom!</strong> Lista de emails com atraso enviado com sucesso.";
			$mensagem = "<div class=\"alert alert-success\" role=\"alert\">".$sucesso."</div>";
				
         } 

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
				echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=listarEmprestimos.php'>";
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
				echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=listarEmprestimos.php'>";
			}
		}		 	 

	 ?>
      
	
		<title>Empréstimos</title>
		  </head>
		  <body>
		 <div class="container"> 
		<legend><span class="glyphicon glyphicon-transfer" aria-hidden="true"></span> Pesquisar Empréstimos</legend>
		 <?php if(isset($_GET['id']) == 1){echo $mensagem;} else{}?>
		 <div style="float:right">
	               <a data-toggle='tooltip' data-placement='top' class="btn btn-warning" title="Enviar" href="listarEmprestimos.php?id=1"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> Enviar Emails de Atrasos</a>
					 </div>
		<form class="form-search" action="listarEmprestimos.php" method="post">
	
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
	<th>Id</th>
    <th>Usuário</th>
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
  
   while($emprestimo = mysqli_fetch_assoc($lista))
   {
   
   ?>
 
		<?php 
	$dateHoje = date('Y-m-d');
	if((strtotime($dateHoje) > strtotime($emprestimo["dataprevista"])) && $emprestimo["status"] == 0)
		echo "<tr class=\"danger\"><td><span data-toggle='tooltip' data-placement='top' title='Empréstimo atrasado.' style='color:#a94442' class='glyphicon glyphicon-alert' aria-hidden='true'></span></td>";
	else if($emprestimo["status"] == 1)
		echo "<tr class=\"success\"><td><span data-toggle='tooltip' data-placement='top' title='Empréstimo Devolvido.' style='color:#4cae4c' class='glyphicon glyphicon-ok-sign' aria-hidden='true'></span></td>";
	else
		echo "<tr><td><span data-toggle='tooltip' data-placement='top' title='Em andamento.' style='color:' class='glyphicon glyphicon-export' aria-hidden='true'></span></td>";
	
	?>
	
	<td value="<?php echo $emprestimo["id"]?>"><?php echo $emprestimo ["id"]?></td>
    <td value="<?php echo $emprestimo["idusuario"]?>"><?php echo $emprestimo ["nomeusuario"]?></td>
    <td value="<?php echo $emprestimo["idlivro"]?> "><?php echo $emprestimo ["nomelivro"]?></td>
	 <td><?php echo date('d/m/Y', strtotime($emprestimo ["dataemprestimo"]))?></td>
	   <td><?php echo date('d/m/Y', strtotime($emprestimo ["dataprevista"]))?></td>
	    <td value="<?php echo $emprestimo["renovacao"]?> "><?php echo $emprestimo ["renovacao"]?> vez(es)</td>
		 <td><?php if($emprestimo ["datarenovacao1"] == '0000-00-00'){echo "-";}else{echo date('d/m/Y', strtotime($emprestimo ["datarenovacao1"]));}?></td>
		 <td><?php if($emprestimo ["datarenovacao2"] == '0000-00-00'){echo "-";}else{echo date('d/m/Y', strtotime($emprestimo ["datarenovacao2"]));}?></td>
	  <td><?php if($emprestimo ["datadevolucao"] == '0000-00-00'){echo "-";}else{echo date('d/m/Y', strtotime($emprestimo ["datadevolucao"]));}?></td>


	<!--<a data-toggle='tooltip' data-placement='top' class="btn btn-danger" title="Excluir" href="listarEmprestimos.php?id=<?php //echo $emprestimo["id"]?>" ><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>-->
	<td>
	<?php 
	if((strtotime($dateHoje) < strtotime($emprestimo["dataprevista"])) && $emprestimo["status"] == 0 && $emprestimo["renovacao"] < 2)
	{
	echo "<a data-toggle='tooltip' data-placement='top' class='btn btn-success' title='Renovar Empréstimo' href='listarEmprestimos.php?id=".$emprestimo["id"]."'><span class='glyphicon glyphicon-refresh' aria-hidden='true'></span></a>";
   }
   else
   {}
?>
<?php 
	/*if((strtotime($dateHoje) > strtotime($emprestimo["dataprevista"])) && $emprestimo["status"] == 0)
	{
		
	echo "<a data-toggle='tooltip' data-placement='top' class='btn btn-warning' title='Enviar email referente a atraso.' href='listarEmprestimos.php?idusuario=".$emprestimo['idusuario']."'><span class='glyphicon glyphicon-envelope' aria-hidden='true'></span></a>";
	}*/
	?>
  <?php 
   }
    ?>
  
    </tbody>
    </table></div></div></div>

	</body>
</html>