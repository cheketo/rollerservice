<?php
//============================================================+
// File name   : example_006.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 006 for TCPDF class
//               WriteHTML and RTL support
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: WriteHTML and RTL support
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Alejandro Romero');
$pdf->SetTitle('Ejemplo');
$pdf->SetSubject('Cotiazación');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(10, 10, 9);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/spa.php')) {
	require_once(dirname(__FILE__).'/lang/spa.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set font
$pdf->SetFont('helvetica', '', 10);

// add a page
$pdf->AddPage();

$pdf->SetLineStyle( array( 'width' => 0.5, 'color' => array(0,0,0)));

$pdf->Line(10,10,$pdf->getPageWidth()-9,10); 
$pdf->Line($pdf->getPageWidth()-9,10,$pdf->getPageWidth()-9,$pdf->getPageHeight()-9);
$pdf->Line(10,$pdf->getPageHeight()-9,$pdf->getPageWidth()-9,$pdf->getPageHeight()-9);
$pdf->Line(10,10,10,$pdf->getPageHeight()-9);


$html = '<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="3">
			<span style="font-size:20px;">&nbsp;<b>ROLLER SERVICE S.A.</b></span>
		</td>
		<td>
			<span style="font-size:14px;"><b>N&uacute;mero: 669</b><br>
			Fecha: 23/11/2017<br>
			ORIGINAL
			</span>
		</td>
	</tr>
	<tr><td colspan="4"><span style="font-size:20px;text-align:center;font-weight:bold;">Cotizaci&oacute;n</span><br></td></tr>
	<tr style="font-size:10px;">
	    <td colspan="3">
	       <span>Avenida Caseros 3217 - Buenos Aires - Argentina</span><br>
	       <span>Tel&eacute;fono - FAX: +54 11 4912-1100 / L&iacute;neas Rotativas</span><br>
	       <span>Web: <a href="https://rollerservice.com.ar">www.rollerservice.com.ar</a></span><br>
	       <span>Email: <a href="mailto:ventas@rollerservice.com.ar">ventas@rollerservice.com.ar</a></span><br>
	    </td>
	    <td>
	        <span>CUIT: 33-64765677-9</span><br>
	        <span>IIBB: 901-963789-5</span><br>
	        <span>IVA: Responsable Inscripto</span><br>
	    </td>
	</tr>
</table>
<hr>
<div>&nbsp;</div>
<table border="0" cellspacing="0" cellpadding="0" style="font-size:10px;">
	<tr>
		<td>
		    Se&ntilde;or/es:
		</td>
		<td colspan="5">
		    Aceros Munro S.R.L. (7)<br>
		    Virrey Olaguer y Feli&uacute; 5260 - Munro - Buenos Aires
		</td>
		<td colspan="6">
		    &nbsp;
		</td>
	</tr>
	<tr>
		<td>
		    Tel&eacute;fono:
		</td>
		<td colspan="5">
		    4761-2004
		</td>
		<td colspan="6">
		    &nbsp;
		</td>
	</tr>
	<tr>
		<td>
            Contacto:
		</td>
		<td colspan="5">
		    &nbsp;
		</td>
		<td colspan="6">
		    &nbsp;
		</td>
	</tr>
	<tr>
		<td>
		    Correo:
		</td>
		<td colspan="3">
		    &nbsp;
		</td>
		<td colspan="4">
		    &nbsp;
	    </td>
    </tr>
</table>
<br>
<br>
<table border="0" cellspacing="0" cellpadding="3" style="font-size:10px;">
    <tr>
        <th align="center" colspan="2" style="border-top:1px solid #000;border-bottom:1px solid #000;">
            Cantidad
        </th>
        <th align="center" colspan="4" style="border-left:1px solid #000;border-top:1px solid #000;border-bottom:1px solid #000;">
            Art&iacute;culo - Descripci&oacute;n
        </th>
        <th align="center" colspan="2" style="border-left:1px solid #000;border-top:1px solid #000;border-bottom:1px solid #000;">
            Entrega
        </th>
        <th align="center" colspan="2" style="border-left:1px solid #000;border-top:1px solid #000;border-bottom:1px solid #000;">
            Precio Unitario
        </th>
        <th align="center" colspan="2" style="border-left:1px solid #000;border-top:1px solid #000;border-bottom:1px solid #000;">
            Precio Total
        </th>
    </tr>
    <tr style="background-color:#CCC;">
        <td colspan="2" align="center">1.00</td>
        <td colspan="4">SAE 52100 65x81</td>
        <td colspan="2" align="center">3 D&iacute;as</td>
        <td colspan="2" align="right">$ 315.92</td>
        <td colspan="2" align="right">$ 315.92</td>
    </tr>
    <tr style="background-color:#CCC;">
        <td colspan="12"></td>
    </tr>
    <tr>
        <td colspan="6" style="border-top:1px solid #000;border-bottom:1px solid #000;">MONEDA: Pesos</td>
        <td colspan="6" style="border-top:1px solid #000;border-bottom:1px solid #000;border-left:1px solid #000;">VALIDEZ: 10 D&iacute;as</td>
    </tr>
    <tr>
        <td colspan="2" align="left" style="border-top:1px solid #000;border-bottom:1px solid #000;">SUBTOTAL:</td>
        <td colspan="2" align="right" style="border-top:1px solid #000;border-bottom:1px solid #000;">$ 315.92</td>
        
        <td colspan="2" align="left" style="border-left:1px solid #000;border-top:1px solid #000;border-bottom:1px solid #000;">IVA:</td>
        <td colspan="2" align="right" style="border-top:1px solid #000;border-bottom:1px solid #000;">$ 66.34</td>
        
        <td colspan="2" align="left" style="border-left:1px solid #000;border-top:1px solid #000;border-bottom:1px solid #000;">TOTAL:</td>
        <td colspan="2" align="right" style="border-top:1px solid #000;border-bottom:1px solid #000;">$ 382.26</td>
    </tr>
</table>
';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->lastPage();

$pdf->Output('example.pdf', 'I');

?>