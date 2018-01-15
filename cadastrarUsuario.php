<html>
   <head>
      <?php
        INCLUDE "header.php";
		
		if (isset($_POST['nomeusuario']) && isset($_POST['login']) && isset($_POST['senha']) && isset($_POST['email']))
		{
		
			$nomeusuario = $_POST['nomeusuario'];
			$login = $_POST['login'];
			$senha = MD5($_POST['senha']);
			$email = $_POST['email'];
			
			if(isset($_POST["Inserir"]))
			{
				$resultado= mysqli_query($connection,"SELECT * FROM usuario WHERE login = '$login' or email = '$email'");
				if (mysqli_num_rows($resultado) > 0) 
				{
					$alerta = "O login ou email já existe no nosso banco.";
					$mensagem = "<div class=\"alert alert-warning\" role=\"alert\">".$alerta."</div>";	 
				}
				else 
				{
					$sql = "INSERT INTO usuario (nomeusuario, login, senha, email) VALUES ('$nomeusuario','$login', '$senha', '$email')";
					mysqli_query($connection, $sql);
				
					$sucesso = "<strong>Muito Bom!</strong> Usuário cadastrado com sucesso.";
					$mensagem = "<div class=\"alert alert-success\" role=\"alert\">".$sucesso."</div>";

					
				}
		
			}
			if(isset($_POST["Alterar"]))
			{
				$id = isset($_POST["id"]);
				$nomeusuario = isset($_POST["nomeusuario"]);
				$login = isset($_POST["login"]);
				$senha = isset($_POST["senha"]);
				$email = isset($_POST["email"]);
         
				$sqlAlterar = "UPDATE usuario SET nomeusuario = '$nomeusuario', 
				login = '$login', 
				senha = '$senha',
				email = '$email'
				WHERE id = '$id'";
         
				mysqli_query($connection, $sqlAlterar);
				$sucesso = "<strong>Muito Bom!</strong> Usuário alterado com sucesso.";
				$mensagem = "<div class=\"alert alert-success\" role=\"alert\">".$sucesso."</div>";
         	
			}
		}
		
		//aqui recebe os dados para alteração
         
         if(isset($_GET["id"]))
         {
			$botao = "<button class='btn btn-success' name='Alterar' type='submit'><span class='glyphicon glyphicon-edit' aria-hidden='true'></span> alterar</button>";
			$id = $_GET["id"];
			$sql = "select * from usuario where id=$id";
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
   <fieldset>
      <div class="container">
  	  
               <form class="form-horizontal" action="cadastrarUsuario.php" method="post" name="f1">
                  <legend><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Cadastro de Usuário</legend>
                  <?php if(isset($_POST["Alterar"]) || isset($_POST["Inserir"])){ echo $mensagem;} else{}?>
                <div id="msgemail"></div>
                           <div class="form-group">
                              <label class="control-label col-sm-2">Id</label>
							   <div class="col-sm-10">
                              <input type="text" class="form-control" disabled name="id" value="<?php echo $linha["id"]?>"></div>
                           </div>
                      
                   
                           <div class="form-group">
                              <label class="control-label col-sm-2">Nome</label>
							  <div class="col-sm-10">
                              <input type="text" class="form-control" name="nomeusuario" value="<?php echo $linha["nomeusuario"]?> "></input></div>
                           </div>
						   
						   <div class="form-group">
                              <label class="control-label col-sm-2">Email</label>
							  <div class="col-sm-10">
                              <input type="email" onblur="validacaoEmail(f1.email)" id="email" class="form-control" name="email" value="<?php echo $linha["email"]?> "></input></div>
                           </div>
             
                           <div class="form-group">
                              <label class="control-label col-sm-2">Login</label>
							  <div class="col-sm-10">
                              <input type="text" class="form-control" placeholder="login" name="login" value="<?php echo $linha["login"]?> "></input></div>
                           </div>
                     
                 
                           <div class="form-group">
                              <label class="control-label col-sm-2">Senha</label>
							  <div class="col-sm-10">
                              <input type="password" class="form-control" placeholder="****" name="senha" value="<?php echo $linha["senha"]?> "></input></div>
                           </div>
                     <div class="form-group">
					   <div class="col-sm-offset-2 col-sm-10">
                     <button class="btn btn-success" name="Inserir" type="submit"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Salvar</button>
                     
					 <?php if(isset($_GET["id"])){ echo $botao;} else{}?>
                  </div>
                  </div>
               </form>
			   
            </div>
			</fieldset>
			<script language="Javascript">
function validacaoEmail(field) {
usuario = field.value.substring(0, field.value.indexOf("@"));
dominio = field.value.substring(field.value.indexOf("@")+ 1, field.value.length);

if ((usuario.length >=1) &&
    (dominio.length >=3) && 
    (usuario.search("@")==-1) && 
    (dominio.search("@")==-1) &&
    (usuario.search(" ")==-1) && 
    (dominio.search(" ")==-1) &&
    (dominio.search(".")!=-1) &&      
    (dominio.indexOf(".") >=1)&& 
    (dominio.lastIndexOf(".") < dominio.length - 1)) {
document.getElementById("msgemail").innerHTML="";
//alert("E-mail valido");
var elemento = document.querySelector("#email");
elemento.style.borderColor = "";

}
else{
document.getElementById("msgemail").innerHTML="<div class='alert alert-danger' role='alert'><strong>Atenção!</strong> Email Inválido. </div>";
//alert("E-mail invalido");
document.f1.email.focus();
var elemento = document.querySelector("#email");
elemento.style.borderColor = "#D93600";
}
}
</script>
   </body>
</html>