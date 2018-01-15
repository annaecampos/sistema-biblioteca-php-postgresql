<?php
	
	
	//$pdf = new FPDF();

	
	$pdf = new FPDF("P","mm","A4");
	//$pdf->CharSet_in = 'UTF-8';
	
	$pdf->setAuthor("Ana Campos");
	$pdf->setTitle("<h1>Relatório de Atrasos</h1>",0);

	
	$pdf->AliasNbPages('{np}');

	$pdf->SetMargins(10,30,10);


	$pdf->AddPage();
	
	$pdf->SetFont('Arial','B',14);
	//IMAGEM
	//$pdf->Image("imagemHome.jpg", 0,10,30,30);
	$pdf->SetTextColor(128,128,128);
		$pdf->SetFillColor(255,255,255);
	$pdf->cell(0,20,utf8_decode("Olá,").$nomearquivo." verificamos algum(s) livro(s) em atraso.",0,3,"C", 1);

	

 	$pdf->ln(10);
	
	//Um exemplo: campo de Observações
	
	$pdf->Cell(70,10,utf8_decode("Observações:"),0,1,'L', 1);
	$pdf->SetFont('Arial','',12);
	$pdf->MultiCell(0,10,utf8_decode("Mantenha sempre seus empréstimos em dia assim você evita de pagar multas."),0,'J', 1);
	$pdf->setFont('arial','B',14);
	$pdf->cell(70,20,"Listagem de Livros em Atraso:",0,1,"L", 1);
		$pdf->SetFont('Arial','B',12);
	// largura padrão das colunas
	$largura = 63;
	// altura padrão das linhas das colunas
	$altura = 20;
	$pdf->SetFillColor(220,220,220);
	// criando os cabeçalhos para 5 colunas
	$pdf->Cell($largura, $altura, 'Livro', 1, 0, 'C',1);
	$pdf->Cell($largura, $altura, utf8_decode('Data Empréstimo'), 1, 0, 'C', 1);
	$pdf->Cell($largura, $altura, 'Data Prevista Para Entrega', 1, 0, 'C', 1);
	
	// pulando a linha
	$pdf->Ln($altura);
	$pdf->SetFont('Arial','',12);
	$pdf->SetFillColor(178,34,34);
	$pdf->SetTextColor(220,220,220);
		$pdf->Cell($largura, $altura, utf8_decode($nomelivro), 1, 0, 'C',1);
		$pdf->Cell($largura, $altura, utf8_decode($dataemprestimo), 1, 0, 'C',1);
		$pdf->Cell($largura, $altura, utf8_decode($dataprevista), 1, 0, 'C',1);
		$pdf->Ln($altura);


	// Gerando um rodapé simples
	$pdf->Line(25, 270, 185, 270); // insere linha divisória
	$pdf->SetXY(25,270); //posição para o texto
	$data=date("d/m/Y"); //pegando data e hora da criação do PDF
		$pdf->SetFillColor(105,105,105);
	$conteudo=$data.utf8_decode(" Pág. ").$pdf->PageNo(); //pegando o número da página
	
	$texto="Gerado Por Sistema de Bibliotecas.";
	$pdf->Cell(80,5,$texto,0,0,"L", 1); //Insere célula de texto alinhado à esquerda
	$pdf->Cell(80,5,$conteudo,0,0,"R", 1); //Insere célula de texto alinhado à direita
$pdf->AddPage();
$pdf->SetTextColor(0,0,255);
$pdf->SetFont('Arial','',14);


	$pdf->Output($nomearquivo.".pdf",'F');
?>