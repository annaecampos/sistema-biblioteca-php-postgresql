<!DOCTYPE html>
<html>
<head>
<?php
   INCLUDE 'header.php';
?>
<meta charset="ISO-8859-1">
<title>Carregamento (UPLOAD) de arquivos em PHP (salva caminho no banco) </title>
</head>
<body>
	<legend><i class="icon-edit"></i>Upload de imagem</legend>
	
	
		<form align="center" method="POST" action="recebeupload.php" enctype="multipart/form-data">
		<div class="controls controls-row">
		<div class="control-group span">
	 
			<input type="file" name="arquivo"/></div></div>
			
			<div class="form-actions">
			<div class="pull-right">
			<button class="btn btn-success" type="submit" name="Enviar"><i class="icon-ok icon-white"></i>Salvar</button></div></div>
			
		</form>
	
	
<!--LISTAR-->
		<legend><i class="icon-edit"></i>Listagem de imagens</legend>
		<table border="1" align="center">
		<tr >
		<td>ID</td>
		<td>Nome</td>
		<td>Excluir</td>
		<td>Visualizar</td>

		</tr>

		<?php
		//fiz isso lá no pgAdmin III
		//agora para verificar, damos selext * from animal;
		//consulta No PHP
		$buscatodos = mysqli_query($connection, "select * from foto");

		//para percorrer todos
		while ($linha = mysqli_fetch_assoc($buscatodos)){
			echo "<tr><td>".$linha["id"]."</td>";
			echo "<td>".$linha["nome"]."</td>";
		//	echo "<td> <a href=editarcarro.php?id=".$linha["id"]."&nome=".$linha["nome"].">Editar</a></td>";
			echo "<td> <a href=excluir.php?id=".$linha["id"]."&nome=".$linha["nome"]."><img src=iconexclusao.jpg alt=icon.jpg title='Excluir imagem'/></a></a></td>";
			echo "<td> <a href=mostraimagem.php?foto=".$linha["foto"]."><img src=icon.jpg alt=icon.jpg title='Ver imagem'/></a></td>";
		}
		//echo "<td><img src='".$imagem_gerada."' alt=Foto de exibição /> </td></tr>";
		?>
		</table>

<hr/>
<br>
<br>
	</body>
</html>