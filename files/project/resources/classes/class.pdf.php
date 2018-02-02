<?php
// Include the main TCPDF library (search for installation path).
require_once('../../../../vendors/tcpdf/tcpdf.php');

class Pdf extends TCPDF
{
    var $Pdf;
    var $OutputType = "I";
    const SAVE_PATH = '../../../../skin/files/pdf/';
    
    function __construct()
    {
        // create new PDF document
        // $this->Pdf = new TCPDF();
        parent::__construct(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // set document information
        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('Roller Service');
        $this->SetTitle('Ejemplo');
        $this->SetSubject('Documento');
        $this->SetKeywords('TCPDF, PDF, example, test, guide');
        
        // set default header data
        $this->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);
        
        // set header and footer fonts
        $this->setHeaderFont(Array('helvetica', '', 10));
        $this->setFooterFont(Array('helvetica', '', 10));
        
        // set default monospaced font
        $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        
        // set margins
        // $this->SetMargins(10, 10, 9);
        $this->SetHeaderMargin(10);
        $this->SetFooterMargin(0);
        
        // set auto page breaks
        $this->SetAutoPageBreak(TRUE, 10);
        
        // set image scale factor
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);
        
        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/spa.php')) {
        	require_once(dirname(__FILE__).'/lang/spa.php');
        	$this->setLanguageArray($l);
        }
    }
    
    public function Header() {
        $HeaderData = $this->getHeaderData();
        // add a page box
        $this->SetLineStyle( array( 'width' => 0.5, 'color' => array(0,0,0)));
        $this->Line(10,10,$this->getPageWidth()-9,10); 
        $this->Line($this->getPageWidth()-9,10,$this->getPageWidth()-9,$this->getPageHeight()-9);
        $this->Line(10,$this->getPageHeight()-9,$this->getPageWidth()-9,$this->getPageHeight()-9);
        $this->Line(10,10,10,$this->getPageHeight()-9);
        
        $this->SetFont('helvetica', '', 10);
        $this->writeHTML($HeaderData['string']);
    }
    
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-10);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
    
    public function SetOutputType($OutputType="I")
    {
        $this->OutputType = $OutputType;
    }
    
    private function GetOutPut($Name,$Path="")
    {
        $Path = $Path?$Path:self::SAVE_PATH;
        if(!file_exists($Path) && !is_dir($Path))
		{
			mkdir($Path);
		}
		if($this->OutputType=="I")
		{
            $this->Output($Name, $this->OutputType);
            return 1;
        }else{
            $File = realpath(__DIR__.'/'.$Path).'/'.$Name.".pdf";
            $this->Output($File, $this->OutputType);
            echo $Path.$Name.".pdf";
            return $Path.$Name.".pdf";
        }
    }
    
    public function Quotation($QID,$ShowBrands="Y",$ShowExtra="Y")
    {
        $Quotation = new Quotation($QID);
        $Data = $Quotation->GetData();
        $Company = new Company($Data['company_id']);
        
        if(!$Data['email'])
        {
            $Data['email'] = $Company->Data['email'];
        }
        
        // set header and footer off
        // $this->setPrintHeader(false);
        // $this->setPrintFooter(false);
        
        // Set Page Margin (whitout header)
        $this->SetMargins(10, 90, 9);
        
        // set font
        $this->SetFont('helvetica', '', 10);
        
        $HeaderHTML = '<table border="0" cellspacing="0" cellpadding="0">
        	<tr>
        		<td colspan="3">
        			<span style="font-size:20px;">&nbsp;<b>ROLLER SERVICE S.A.</b></span>
        		</td>
        		<td>
        			<span style="font-size:14px;"><b>N&uacute;mero: '.$QID.'</b><br>
        			Fecha: '.Core::FromDBToDate($Data['quotation_date']).'<br>
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
        		    '.$Data['company'].'<br>
        		    '.$Company->Data['address'].' - '.$Company->Data['province_short'].'
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
        		    '.$Company->Data['phone'].'
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
        		    '.$Data['agent'].'
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
        		    '.$Data['email'].'
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
        </table>';
        
        $this->setHeaderData($ln='', $lw=0, $ht='', $HeaderHTML, $tc=array(0,0,0), $lc=array(0,0,0));
        
        // add a page
        $this->AddPage();
        
        
        $HTML = '
        <table border="0" cellspacing="0" cellpadding="3" style="font-size:10px;">
            ';
        foreach($Data['items'] as $Item)
        {
            $Brand = $ShowBrands=="Y"? ' <br><span style="font-size:8px;">Marca: '.$Item['brand'].'</span>':'';
            $Category =  $Item['category'].' - ';
            $RowBackground = $RowBackground=="EEE"?"DDD":"EEE";
            $Days = $Item['days']>0? $Item['days'].' D&iacute;as':'Inmediata';
            $HTML.= '<tr style="background-color:#'.$RowBackground.';">
                    <td colspan="2" align="center">'.$Item['quantity'].'</td>
                    <td colspan="4">'.$Category.$Item['code'].$Brand.'</td>
                    <td colspan="2" align="center">'.$Days.'</td>
                    <td colspan="2" align="right">'.$Item['currency'].' '.$Item['price'].'</td>
                    <td colspan="2" align="right">'.$Item['currency'].' '.$Item['total_item'].'</td>
                </tr>';
            $S = $Data['expire_days']>1? "s":"";
            $IVA = round(($Data['total_quotation']*21)/100,2);
        }
        $Extra = $ShowExtra=="Y"?$Data['extra']:'';
        $RowBackground = $RowBackground=="EEE"?"DDD":"EEE";
        $HTML.= '<tr style="background-color:#'.$RowBackground.';">
                <td colspan="12" align="center">'.$Extra.'</td>
            </tr>
            <tr>
                <td colspan="6" style="border-top:1px solid #000;border-bottom:1px solid #000;">MONEDA: '.$Data['currency_name'].'</td>
                <td colspan="6" style="border-top:1px solid #000;border-bottom:1px solid #000;border-left:1px solid #000;">VALIDEZ: '.$Data['expire_days'].' D&iacute;a'.$S.'</td>
            </tr>
            <tr>
                <td colspan="2" align="left" style="border-top:1px solid #000;border-bottom:1px solid #000;">SUBTOTAL:</td>
                <td colspan="2" align="right" style="border-top:1px solid #000;border-bottom:1px solid #000;">'.$Data['currency'].' '.$Data['total_quotation'].'</td>
                
                <td colspan="2" align="left" style="border-left:1px solid #000;border-top:1px solid #000;border-bottom:1px solid #000;">IVA:</td>
                <td colspan="2" align="right" style="border-top:1px solid #000;border-bottom:1px solid #000;">'.$Data['currency'].' '.$IVA.'</td>
                
                <td colspan="2" align="left" style="border-left:1px solid #000;border-top:1px solid #000;border-bottom:1px solid #000;">TOTAL:</td>
                <td colspan="2" align="right" style="border-top:1px solid #000;border-bottom:1px solid #000;">'.$Data['currency'].' '.($Data['total_quotation']+$IVA).'</td>
            </tr>
        </table>
        ';
        
        // output the HTML content
        $this->writeHTML($HTML, true, false, true, false, '');
        $this->lastPage();
        $FileName = "CotizacionRollerServiceNro".$QID;
        $FilePath = self::SAVE_PATH."quotation/".$QID."/";
        $URL = $FilePath.$FileName.".pdf";
        if(file_exists($URL))
        {
            rename($URL,$FilePath.$QID."-".sha1($this->TmpName.date("Y-m-d H:i:s").".pdf"));
        }
        return $this->GetOutPut($FileName,$FilePath);
    }
}
?>