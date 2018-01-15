<html>
   <head>
      <?php
         INCLUDE "header.php";
		 if (isset($_POST['nomelivro']) && isset($_POST['quantidade']))
		{
         $nomelivro = $_POST['nomelivro'];
         $quantidade = $_POST['quantidade'];
		 $quantidadedisponivel = $quantidade;
         
         if(isset($_POST["Inserir"]))
         {
         
         $sql = "INSERT INTO livro (nomelivro, quantidade, quantidadedisponivel) VALUES ('$nomelivro','$quantidade', '$quantidadedisponivel')";
         mysqli_query($connection, $sql);
		$sucesso = "<strong>Muito Bom!</strong> Livro cadastrado com sucesso.";
		$mensagem = "<div class=\"alert alert-success\" role=\"alert\">".$sucesso."</div>";	 
         }
	}
         if(isset($_POST["Alterar"]))
         {	
			
         $id = isset($_POST["id"]);	
		$sqlSelect = "select * from livro where id = '$id'";	
		$row = mysqli_fetch_row($connection, $sqlSelect);
		$quantidadeemprestado = $row[4];
		$quantidadereservado = $row[5];
         $nomelivro = isset($_POST["nomelivro"]);
         $quantidade = isset($_POST["quantidade"]);
		 $quantidadedisponivel = $quantidade - ($quantidadeemprestado + $quantidadereservado);
         
         $sql = "UPDATE livro SET nomelivro = '$nomelivro', 
           quantidade = '$quantidade',
		   quantidadedisponivel = '$quantidadedisponivel'
           WHERE id = '$id'";
         
         mysqli_query($connection, $sql);
		 $sucesso = "<strong>Muito Bom!</strong> Livro alterado com sucesso.";
				$mensagem = "<div class=\"alert alert-success\" role=\"alert\">".$sucesso."</div>";
         
         }	
         //aqui recebe os dados para alteração
         
         if(isset($_GET["id"]))
         {
			 $botao = "<button class=\"btn btn-success\" name=\"Alterar\" type=\"submit\"><span class=\"glyphicon glyphicon-edit\" aria-hidden=\"true\"></span> alterar</button>";
			$id = $_GET["id"];
			$sql = "select * from livro where id=$id";
			$resultado = mysqli_query($connection,$sql);
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
         <form class="form-horizontal" action="cadastrarLivro.php" method="post">
            <legend><span class="glyphicon glyphicon-book" aria-hidden="true"></span> Cadastro de Livro</legend>
		    <?php if(isset($_POST["Alterar"]) || isset($_POST["Inserir"])){ echo $mensagem;} else{}?>
            
            <div class="form-group">
               <label class="control-label col-sm-2">Id</label>
               <div class="col-sm-10">
                  <input type="text" class="form-control" disabled name="id" value="<?php echo $linha["id"]?> "/>
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-sm-2">Nome</label>
               <div class="col-sm-10">
                  <input type="text" class="form-control" name="nomelivro" value="<?php echo $linha["nomelivro"]?> "></input>
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-sm-2">Quantidade</label>
               <div class="col-sm-10">
                  <input type="text" class="form-control" placeholder="quantidade" name="quantidade" value="<?php echo $linha["quantidade"]?> "></input>
               </div>
            </div>
            <div class="form-group">
               <div class="col-sm-offset-2 col-sm-10">
                  <button class="btn btn-success" name="Inserir" type="submit"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Salvar</button>
                 	 <?php if(isset($_GET["id"])){ echo $botao;} else{}?>
               </div>
            </div>
         </form>
      </div>
   </body>
</html>