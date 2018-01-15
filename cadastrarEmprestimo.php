<html>
   <head>
      <?php
         INCLUDE "header.php";
		 
         if(isset($_POST["Inserir"]))
         {
			$dataDevolucao = '0000-00-00';
			$idusuario = $_POST["idusuario"];
			$idlivro = $_POST["idlivro"];
			$status = 0;
			$dias = 7;
			$dataemprestimo = $_POST["dataemprestimo"];
			$dataprevista = date('y/m/d', strtotime('+'.$dias.'days', strtotime($dataemprestimo)));
			$resultado= mysqli_query($connection,"SELECT * FROM emprestimo WHERE idusuario = $idusuario and status = 0");
			$resultadoLivroIgual= mysqli_query($connection,"SELECT * FROM emprestimo WHERE idusuario = $idusuario and idlivro = $idlivro and status = 0");
			$resultadoTempo = mysqli_query($connection,"SELECT * FROM emprestimo WHERE idusuario = $idusuario and status = 0 and datediff(now(), dataemprestimo) > 7");
         
			if(mysqli_num_rows($resultado) < 2)
			{
				if (mysqli_num_rows($resultadoTempo) > 0) 
				{
					$erro = "<strong>Importante!</strong> Este usuário possui livros em atraso! Empréstimo <strong>NÃO</strong> efetuado.";
					$mensagem = "<div class=\"alert alert-danger\" role=\"alert\">".$erro."</div>";
					
				}
				else
				{
					if (mysqli_num_rows($resultadoLivroIgual) == 1) 
					{
						$erro = "<strong>Importante!</strong> Este usuário possuí o <strong>mesmo livro</strong> emprestado.";
						$mensagem = "<div class=\"alert alert-danger\" role=\"alert\">".$erro."</div>";
					}
					else
					{
						$selec= mysqli_query($connection,"SELECT * FROM livro WHERE id = $idlivro");
						$row = mysqli_fetch_row($selec);
						$quantidadedisponivel = $row[3];
						$quantidadeemprestado = $row[4];
						
						if ($quantidadedisponivel > 0 )
						{
							$sql = "INSERT INTO emprestimo(datadevolucao, idusuario, idlivro, status, dataemprestimo, dataprevista) 
							values('$dataDevolucao', '$idusuario','$idlivro', '$status', '$dataemprestimo', '$dataprevista')";
							mysqli_query($connection, $sql);
							$qtddisp = $quantidadedisponivel - 1;
							$qtdemprest = $quantidadeemprestado + 1;
							$sql = "UPDATE livro SET  
							quantidadedisponivel = '$qtddisp',
							quantidadeemprestado = '$qtdemprest'
							WHERE id = '$idlivro'";
							mysqli_query($connection, $sql);
         
							$sucesso = "<strong>Muito Bom!</strong> Empréstimo cadastrado com sucesso.";
							$mensagem = "<div class=\"alert alert-success\" role=\"alert\">".$sucesso."</div>";
						}
						else
						{
							$erro = "<strong>Importante!</strong> Livro não disponível para empréstimo.";
							$mensagem = "<div class=\"alert alert-danger\" role=\"alert\">".$erro."</div>";
						}
					}
				}
			}
			else
			{
				
				$erro = "<strong>Importante!</strong> Este usuário já possui dois livros emprestados! Empréstimo NÃO efetuado.";
				$mensagem = "<div class=\"alert alert-danger\" role=\"alert\">".$erro."</div>";
			}
         }
         
         if(isset($_POST["Alterar"]))
         {
			$id = isset($_POST["id"]);		
			$dataDevolucao = isset($_POST["datadevolucao"]);
			$idusuario = isset($_POST["idusuario"]);
			$idlivro = isset($_POST["idlivro"]);
         
         
			$sql = "UPDATE emprestimo SET  
			datadevolucao = '$dataDevolucao', 
			idusuario = '$idusuario',
			idlivro = '$idlivro'
			WHERE id = '$id'";
        
			mysqli_query($sql);
         
			$sucesso = "<strong>Muito Bom!</strong> Empréstimo alterado com sucesso.";
			$mensagem = "<div class=\"alert alert-success\" role=\"alert\">".$erro."</div>";
         }
         
        
        if(isset($_GET["id"]))
        {
			$id = isset($_GET["id"]);
			$sql = "select * from emprestimo where id=$id";
			$resultado = mysqli_query($sql);
			$linha = mysqli_fetch_assoc($resultado);
        }
        else
        {
			$linha = 0; 
        }
         
         
         ?>
   </head>
   <body>
      <div class="container">
         <form class="form-horizontal" action="cadastrarEmprestimo.php" method="post">
            <legend><span class="glyphicon glyphicon-transfer" aria-hidden="true"></span> Cadastro de Empréstimo</legend>
                 <?php if(isset($_POST["Alterar"]) || isset($_POST["Inserir"])){ echo $mensagem;} else{}?>
            <div class="form-group">
               <label class="control-label col-sm-2">id</label>
               <div class="col-sm-10">
                  <input type="text" class="form-control" disabled name="id" value="<?php echo $linha["id"]?> "/>
               </div>
            </div>
       
            <div class="form-group">
               <label class="control-label col-sm-2">Usuário</label>
               <div class="col-sm-10">
                  <select class="form-control" name="idusuario" value="<?php echo $linha["idusuario"]?> ">
                     <?php
                        $sql = "select * from usuario order by nomeusuario";
                        $res = mysqli_query($connection, $sql);
                        $optUsuario = "";
                        while ($linha=mysqli_fetch_assoc($res))
                        {
                        $id = $linha["id"];
                        $nome = $linha["nomeusuario"];
                        
                        if($emprestimo["id"] == $item["idusuario"])
                        {
                        $optUsuario = $optUsuario. "<option value='$id' selected>$nome</option>";
                        }
                        else
                        {
                        $optUsuario = $optUsuario. "<option value='$id'>$nome</option>";
                        }
                        }
                        ?>
                     <option value="">selecione..</option>
                     <?php echo $optUsuario; ?>
                  </select>
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-sm-2">Livro</label>
               <div class="col-sm-10">
                  <select class="form-control" name="idlivro" value="<?php echo $linha["idlivro"]?> ">
                     <?php
                        $sql = "select * from livro where quantidade > 0 order by nomelivro";
                        $res = mysqli_query($connection, $sql);
                        $optLivro = "";
                        while ($linha=mysqli_fetch_assoc($res))
                        {
                        $id = $linha["id"];
                        $nome = $linha["nomelivro"];
                        if($emprestimo["id"] == $item["idlivro"])
                        {
                        $optLivro = $optLivro. "<option value='$id' selected>$nome</option>";
                        }
                        else
                        {
                        $optLivro .= "<option value='$id'>$nome</option>";
                        }
                        }
                        ?>
                     <option value="">selecione..</option>
                     <?php echo $optLivro; ?>
                  </select>
               </div>
            </div>
			 <div class="form-group">
               <label class="control-label col-sm-2">Data empréstimo</label>
			    <div class="col-sm-10">
               <input class="form-control" type="date" placeholder="__/__/__" name="dataemprestimo"></input>
			  </div></div>
            <div class="form-group">
               <div class="col-sm-offset-2 col-sm-10">
                  <button class="btn btn-success" name="Inserir" type="submit"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> salvar</button>
                  <button class="btn btn-success" name="Alterar" type="submit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> alterar</button>
               </div>
            </div>
         </form>
      </div>

   </body>
</html>