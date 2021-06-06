<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PDFController extends Controller
{
	//Content-type: application/pdf
    public function print_report_pdf()
	{
		require('TCPDF-main\tcpdf.php');
		
		// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 001');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print
$html = <<<EOD
<h1>Welcome to <a href="http://www.tcpdf.org" style="text-decoration:none;background-color:#CC0000;color:black;">&nbsp;<span style="color:black;">TC</span><span style="color:white;">PDF</span>&nbsp;</a>!</h1>
<i>This is the first example of TCPDF library.</i>
<p>This text is printed using the <i>writeHTMLCell()</i> method but you can also use: <i>Multicell(), writeHTML(), Write(), Cell() and Text()</i>.</p>
<p>Please check the source code documentation and other examples for further information.</p>
<p style="color:#CC0000;">TO IMPROVE AND EXPAND TCPDF I NEED YOUR SUPPORT, PLEASE <a href="http://sourceforge.net/donate/index.php?group_id=128076">MAKE A DONATION!</a></p>
EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('example_001.pdf', 'I');

return;
		//define('FPDF_FONTPATH', 'font/');
		//include_once('ufpdf/ufpdf.php');
		
		// Начало конфигурации

		$textColour = array( 0, 0, 0 );
		$headerColour = array( 100, 100, 100 );
		$tableHeaderTopTextColour = array( 255, 255, 255 );
		$tableHeaderTopFillColour = array( 125, 152, 179 );
		$tableHeaderTopProductTextColour = array( 0, 0, 0 );
		$tableHeaderTopProductFillColour = array( 143, 173, 204 );
		$tableHeaderLeftTextColour = array( 99, 42, 57 );
		$tableHeaderLeftFillColour = array( 184, 207, 229 );
		$tableBorderColour = array( 50, 50, 50 );
		$tableRowFillColour = array( 213, 170, 170 );
		$reportName = "2009 Widget Sales Report";
		$reportNameYPos = 160;
		$logoFile = "widget-company-logo.png";
		$logoXPos = 50;
		$logoYPos = 108;
		$logoWidth = 110;
		$columnLabels = array( "Q1", "Q2", "Q3", "Q4" );
		$rowLabels = array( "SupaWidget", "WonderWidget", "MegaWidget", "HyperWidget" );
		$chartXPos = 20;
		$chartYPos = 250;
		$chartWidth = 160;
		$chartHeight = 80;
		$chartXLabel = "Product";
		$chartYLabel = "2009 Sales";
		$chartYStep = 20000;

		$chartColours = array(
						  array( 255, 100, 100 ),
						  array( 100, 255, 100 ),
						  array( 100, 100, 255 ),
						  array( 255, 255, 100 ),
						);
		$data = array(
				  array( 9940, 10100, 9490, 11730 ),
				  array( 19310, 21140, 20560, 22590 ),
				  array( 25110, 26260, 25210, 28370 ),
				  array( 27650, 24550, 30040, 31980 ),
				);
				
		// Конец конфигурации

		/**
		  Создаем титульную страницу
		**/
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );
		$pdf->AddPage();

		// Логотип
		//$pdf->Image( $logoFile, $logoXPos, $logoYPos, $logoWidth );

		// Название отчета
		$pdf->SetFont( 'Arial', 'B', 24 );

		$pdf->Ln( $reportNameYPos );

		$pdf->Cell( 0, 15, $reportName, 0, 0, 'C' );

		$pdf->AddPage();
		$pdf->SetTextColor( $headerColour[0], $headerColour[1], $headerColour[2] );
		$pdf->SetFont( 'Arial', '', 17 );
		$pdf->Cell( 0, 15, $reportName, 0, 0, 'C' );

		$pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );
		$pdf->SetFont( 'Arial', '', 20 );
		$pdf->Write( 19, "2009 Was A Good Year" );

		$pdf->Ln( 16 );
		$pdf->SetFont( 'tahoma', '', 12 );
		$pdf->Write( 6, "фывDespite the economic downturn, WidgetCo had a strong year. Sales of the HyperWidget in particular exceeded expectations. The fourth quarter was generally the best performing; this was most likely due to our increased ad spend in Q3." );
		$pdf->Ln( 12 );
		$pdf->Write( 6, "2010 is expected to see increased sales growth as we expand into other countries." );

		$pdf->SetDrawColor( $tableBorderColour[0], $tableBorderColour[1], $tableBorderColour[2] );
		$pdf->Ln( 15 );

		// Создаем строку заголовков таблицы
		$pdf->SetFont( 'Arial', 'B', 15 );

		// Ячейка "PRODUCT"
		$pdf->SetTextColor( $tableHeaderTopProductTextColour[0], $tableHeaderTopProductTextColour[1], $tableHeaderTopProductTextColour[2] );
		$pdf->SetFillColor( $tableHeaderTopProductFillColour[0], $tableHeaderTopProductFillColour[1], $tableHeaderTopProductFillColour[2] );
		$pdf->Cell( 46, 12, " PRODUCT", 1, 0, 'L', true );

		// Остальные ячейки заголовков
		$pdf->SetTextColor( $tableHeaderTopTextColour[0], $tableHeaderTopTextColour[1], $tableHeaderTopTextColour[2] );
		$pdf->SetFillColor( $tableHeaderTopFillColour[0], $tableHeaderTopFillColour[1], $tableHeaderTopFillColour[2] );

		for ( $i=0; $i<count($columnLabels); $i++ ) {
		  $pdf->Cell( 36, 12, $columnLabels[$i], 1, 0, 'C', true );
		}

		$pdf->Ln( 12 );

		// Создаем строки с данными
		$fill = false;
		$row = 0;

		foreach ( $data as $dataRow ) {
			// Создаем левую ячейку с заголовком строки
			$pdf->SetFont( 'Arial', 'B', 15 );
			$pdf->SetTextColor( $tableHeaderLeftTextColour[0], $tableHeaderLeftTextColour[1], $tableHeaderLeftTextColour[2] );
			$pdf->SetFillColor( $tableHeaderLeftFillColour[0], $tableHeaderLeftFillColour[1], $tableHeaderLeftFillColour[2] );
			$pdf->Cell( 46, 12, " " . $rowLabels[$row], 1, 0, 'L', $fill );

			// Создаем ячейки с данными
			$pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );
			$pdf->SetFillColor( $tableRowFillColour[0], $tableRowFillColour[1], $tableRowFillColour[2] );
			$pdf->SetFont( 'Arial', '', 15 );

			for ( $i=0; $i<count($columnLabels); $i++ ) {
			$pdf->Cell( 36, 12, ( '$' . number_format( $dataRow[$i] ) ), 1, 0, 'C', $fill );
			}

			$row++;
			$fill = !$fill;
			$pdf->Ln( 12 );
		}

		/***
		  Создаем график
		***/

		// Вычисляем масштаб по оси X
		$xScale = count($rowLabels) / ( $chartWidth - 40 );

		// Вычисляем масштаб по оси Y
		$maxTotal = 0;

		foreach ( $data as $dataRow ) {
			$totalSales = 0;
			foreach ( $dataRow as $dataCell ) $totalSales += $dataCell;
				$maxTotal = ( $totalSales > $maxTotal ) ? $totalSales : $maxTotal;
		}

		$yScale = $maxTotal / $chartHeight;

		// Вычисляем ширину столбцов
		$barWidth = ( 1 / $xScale ) / 1.5;

		// Добавляем оси:
		$pdf->SetFont( 'Arial', '', 10 );

		// Ось X
		$pdf->Line( $chartXPos + 30, $chartYPos, $chartXPos + $chartWidth, $chartYPos );

		for ( $i=0; $i < count( $rowLabels ); $i++ ) {
			$pdf->SetXY( $chartXPos + 40 +  $i / $xScale, $chartYPos );
			$pdf->Cell( $barWidth, 10, $rowLabels[$i], 0, 0, 'C' );
		}

		// Ось Y
		$pdf->Line( $chartXPos + 30, $chartYPos, $chartXPos + 30, $chartYPos - $chartHeight - 8 );

		for ( $i=0; $i <= $maxTotal; $i += $chartYStep ) {
			$pdf->SetXY( $chartXPos + 7, $chartYPos - 5 - $i / $yScale );
			$pdf->Cell( 20, 10, '$' . number_format( $i ), 0, 0, 'R' );
			$pdf->Line( $chartXPos + 28, $chartYPos - $i / $yScale, $chartXPos + 30, $chartYPos - $i / $yScale );
		}

		// Добавляем метки осей
		$pdf->SetFont( 'Arial', 'B', 12 );
		$pdf->SetXY( $chartWidth / 2 + 20, $chartYPos + 8 );
		$pdf->Cell( 30, 10, $chartXLabel, 0, 0, 'C' );
		$pdf->SetXY( $chartXPos + 7, $chartYPos - $chartHeight - 12 );
		$pdf->Cell( 20, 10, $chartYLabel, 0, 0, 'R' );

		// Создаем столбецы
		$xPos = $chartXPos + 40;
		$bar = 0;

		foreach ( $data as $dataRow ) {
			// Вычисляем суммарное значение по строке данных для продукта
			$totalSales = 0;
			foreach ( $dataRow as $dataCell ) $totalSales += $dataCell;

			// Создаем столбец
			$colourIndex = $bar % count( $chartColours );
			$pdf->SetFillColor( $chartColours[$colourIndex][0], $chartColours[$colourIndex][1], $chartColours[$colourIndex][2] );
			$pdf->Rect( $xPos, $chartYPos - ( $totalSales / $yScale ), $barWidth, $totalSales / $yScale, 'DF' );
			$xPos += ( 1 / $xScale );
			$bar++;
		}

		/***
		  Выводим PDF
		***/
		header("Content-Type:application/pdf; charset=UTF-8");
		//header("Content-Type: application/pdf");
		//$pdf->Output( "report.pdf", "I");
		return new Response($pdf->Output(), 200, array('Content-Type'=>'application/pdf'));
	}
}
