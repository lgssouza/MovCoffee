<?php
	
	require_once('fpdf.php');

	require_once ('teste.php');

 //criar um objeto para gerar o arquivo pdf
  $relPDF = new fpdf();

 // pagina no formato retrato (Portrait) , tipo A4
   $relPDF->addPage();


   //setar um estilo de fonte, fonte verdana, estilo bold "negrito", tamanho 14
   $relPDF->setFont('Times','b','16');
   $titulo = utf8_decode('RELATÓRIO DE VENDAS');
   $relPDF->Cell(0 , 0, $titulo , 0, 5, 'C');

  //espaço de 10 linhas;
   $relPDF->ln(5);

 //setar um estilo de fonte, fonte verdana, estilo bold "negrito", tamanho 14
   $relPDF->setFont('Times','b','14');

 //o método multicell permite escrever em varias linha sem quebrar a célula
 //use a função utf8_decode se tiver problemas com acentuação

  $texto = utf8_decode('Relatório bimestral com apresentação das vendas dos meses de Janeiro e Fevereiro. O gráfico abaixo apresenta os valores de cada mês dos vendedores Pedro e Paulo.');
 
  $relPDF->multicell(0, 5, $texto , 0 , 'J');

  //espaço de 10 linhas; 
   $relPDF->ln(10);

  //imprime a imagem no arquivo PDF  
  $relPDF->Image('teste.png',60,30,null,null,'PNG');


  //espaço de 80 linhas;  $relPDF->ln(80);
  $relPDF->setFont('Times','i','8');
  
  $autor= 'Marcelo Weihmayr';
  $blog = 'http://ubuntuiniciantes.blogspot.com';
  $faceboock ='http://www.facebook.com/iniciantes.doubuntu';
  $twitter = 'https://twitter.com/'; 
  
  $relPDF->cell(0, 5, 'Autor: '.$autor, 0 , 5,'R');
  $relPDF->cell(0, 5, 'Blog : '.$blog, 0 , 5,'R');
  $relPDF->cell(0, 5, 'Facebook : '.$faceboock, 0 , 5,'R');
  $relPDF->cell(0, 5, 'Twitter  : '.$twitter, 0 , 5,'R');

 // saida para downlod do arquivo
	$relPDF->Output();

?>