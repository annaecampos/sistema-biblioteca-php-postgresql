<!DOCTYPE html>
<html>

<head>
	<?php

    $connection = mysqli_connect("localhost", "root", "", "biblioteca3") or die("Não foi possível conectar com o Banco.");
	mysqli_set_charset($connection, "utf8");
	
	$login_cookie = $_COOKIE['login'];
	
	if(isset($login_cookie)) {

    }
	else
    {
		//echo"Bem-Vindo, convidado <br>";
			//header("Location:login.php");

	}
	
 if(isset($_POST["Sair"]))
         {
  setcookie("login");
	header("Location:login.php");
		}
	//$select_db = mysqli_select_db($connection, 'biblioteca');
	

	?>
    <meta http-equiv="Content-Type" Content="text/html" charset="UTF-8">

	<link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/navbar-fixed-top.css" rel="stylesheet">

	
		<title>Sistema Gerenciamento de Biblioteca</title>
	
	</head>
	<body>
 <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Biblioteca</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
    
          </ul>
          <ul class="nav navbar-nav navbar-right" style="margin-top:8px;">
        
	 <li style="margin-left:8px; margin-top:-8px;">
       <a href="" style="color:#3CB371"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php echo $login_cookie; ?></a>
	   
     </li>
	 <li style="margin-left:8px;">
	         <form class="form-horizontal" action="headerUsuarioComum.php" method="post"><button data-toggle='tooltip' data-placement='bottom' title="Sair" class="btn btn-danger" type="submit" name="Sair"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></form>
	   </li>
          </ul>
		  
        </div><!--/.nav-collapse -->
      </div>
    </nav>
 <script src="js/jquery-3.2.1.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    
    <script src="js/bootstrap.min.js"></script>
	 	<script>
	$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})</script>

	</body>
	</html>