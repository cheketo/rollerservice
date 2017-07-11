<?php

class Invoice 
{
	use SearchList;
	
	var	$ID;
	var $Data;
	var $Items 			= array();
	var $Table			= "invoice";
	var $TableID		= "invoice_id";
	
	const DEFAULTIMG	= "../../../skin/images/invoices/default.png";

	public function __construct($ID=0)
	{
		
		if($ID!=0)
		{
			$Data = Core::Select($this->Table.' a INNER JOIN currency b ON (a.currency_id=b.currency_id) INNER JOIN invoice_operation c ON (c.operation_id=a.operation_id)',"a.*,b.prefix as currency,c.operation",$this->TableID."=".$ID);
			
			$this->Data = $Data[0];
			$this->ID = $ID;
		}
	}
	
	public function GetEntity()
	{
		if(!$this->Data['entity'])
		{
			if($this->Data['operation']=='I')
			{
				$Entity = Core::Select('provider a LEFT JOIN geolocation_province b ON (b.province_id=a.province_id)',"a.*,b.short_name as province","a.provider_id=".$this->Data['entity_id']);
				// echo $this->LastQuery();
			}else{
				$Entity = Core::Select('customer a INNER JOIN customer_branch b ON (b.customer_id=a.customer_id) INNER JOIN geolocation_province c ON (c.province_id=b.province_id)',"a.*,c.short_cname as province","a.customer_id=".$this->Data['entity_id']);
				// echo $this->LastQuery();
			}
			$this->Data['entity'] = $Entity[0];
		}
		return $this->Data['entity'];
	}
	
	public function GetItems()
	{
		if(empty($this->Data['items']))
		{
			$this->Data['items'] = Core::Select($this->Table."_detail","*",$this->TableID."=".$this->ID,'detail_id');
			//echo $this->LastQuery();
		}
		return $this->Data['items'];
	}
	
	public function GetTaxes()
	{
		if(empty($this->Data['taxes']))
		{
			$Taxes = Core::Select("relation_operation_tax a LEFT JOIN tax b ON (a.tax_id=b.tax_id) INNER JOIN relation_invoice_tax c ON (c.tax_id=b.tax_id)","b.name,c.amount,c.percentage","a.operation_id=".$this->Data['operation_id'].' AND c.invoice_id='.$this->ID);
			// echo $this->LastQuery();
			foreach($Taxes as $Key => $Tax)
			{
				if($Tax['percentage']==0)
				{
					$Percentage = Core::Select("relation_cuit_tax","percentage","tax_id=".$Tax['tax_id']." AND cuit=".$this->Data['entity']['cuit']);
					//echo $this->LastQuery();
					$Taxes[$Key] = $Percentage[0]['percentage'];
				}
			}
			$this->Data['taxes'] = $Taxes;
		}
		return $this->Data['taxes'];
	}

	public function GetDefaultImg()
	{
		return self::DEFAULTIMG;
	}
	
	public function GetImg()
	{
		return $this->GetDefaultImg();
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// SEARCHLIST FUNCTIONS ///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function MakeRegs($Mode="List")
	{
		$Rows	= $this->GetRegs();
		// echo Core::LastQuery();
		for($i=0;$i<count($Rows);$i++)
		{
			$Row	=	new Invoice($Rows[$i][$this->TableID]);
			$Actions	= 	'<span class="roundItemActionsGroup">';
			$Actions	.=	'<a class="hint--bottom hint--bounce" aria-label="M&aacute;s informaci&oacute;n"><button type="button" class="btn bg-navy ExpandButton" id="expand_'.$Row->ID.'"><i class="fa fa-plus"></i></button></a> ';
			
			
			if($Row->Data['status']=="A")
			{
				// $Actions	.= '<a title="Confirmar" alt="Confirmar" class="activateElement" process="'.PROCESS.'" id="activate_'.$Row->ID.'"><button type="button" class="btn btnGreen"><i class="fa fa-check-circle"></i></button></a>';
				$Actions	.= '<a id="payment_'.$Row->ID.'" class="hint--bottom hint--bounce hint--success" aria-label="Pagar"><button type="button" class="btn btn-success"><i class="fa fa-dollar"></i></button></a> ';
			}
			
			if($Row->Data['status']=="A" || $Row->Data['status']=="F")
			{
				$Actions	.= '<a href="invoice.php?id='.$Row->ID.'" class="hint--bottom hint--bounce hint--info Print" aria-label="Imprimir" status="'.$Row->Data['status'].'" id="print_'.$Row->ID.'"><button type="button" class="btn bg-aqua"><i class="fa fa-print"></i></button></a> ';
			}
			
			if($Row->Data['status']=="P" || $Row->Data['status']=="A")
			{
				$Actions	.= '<a class="hint--bottom hint--bounce hint--info" aria-label="Cargar" href="fill.php?id='.$Row->ID.'" id="store_'.$Row->ID.'"><button type="button" class="btn btn-primary"><i class="fa fa-download"></i></button></a>';	
				$Actions	.= '<a class="hint--bottom hint--bounce hint--error deleteElement" aria-label="Rechazar" process="'.PROCESS.'" id="delete_'.$Row->ID.'"><button type="button" class="btn btnRed"><i class="fa fa-times"></i></button></a>';
			}
			
			// if($Row->Data['status']=="A" || $Row->Data['status']=="C")
			// {
			// 	$Actions	.= '<a class="completeElement" href="../stock/stock_entrance.php?id='.$Row->ID.'" title="Ingresar stock" id="complete_'.$Row->ID.'"><button type="button" class="btn btn-dropbox"><i class="fa fa-sign-in"></i></button></a>';
			// }
			
			// if($Row->Data['status']!="F" && $Row->Data['status']!="Z")
			// {
			// 	$Actions	.= '<a title="Editar" alt="Editar" href="edit.php?status='.$Row->Data['status'].'&id='.$Row->ID.'"><button type="button" class="btn btnBlue"><i class="fa fa-pencil"></i></button></a>';
			// }
			$Actions	.= '</span>';
			// echo '<pre>';
			// print_r($Row->Data['items']);
			// echo '</pre>';
			$Date = explode(" ",$Row->Data['due_date']);
			$OrderDate = implode("/",array_reverse(explode("-",$Date[0])));
			
			$Items = '<div style="margin-top:10px;">';
			$I=0;
			$ItemsReceived = 0;
			$ItemsTotal = 0;
			$Row->Data['items'] = $Row->GetItems();
			foreach($Row->Data['items'] as $Item)
			{
				$I++;
				$RowClass = $I % 2 != 0? 'bg-gray':'bg-gray-active';
				
				
				$ItemTotal = $Row->Data['currency']." ".$Item['total'];
				$ItemPrice = $Row->Data['currency']." ".$Item['price'];
				$ItemQuantity = $Item['quantity'];
				
				$Stock = '<div class="col-md-3 hideMobile990">
									<div class="listRowInner">
										<span class="listTextStrong">Cantidad</span>
										<span class="listTextStrong"><span class="label label-primary">'.$ItemQuantity.'</span></span>
									</div>
								</div>';
				
				$Items .= '
							<div class="row '.$RowClass.'" style="padding:5px;">
								<div class="col-md-3 col-sm-5">
									<div class="listRowInner">
										<span class="listTextStrong">'.$Item['description'].'</span>
										
									</div>
								</div>
								<div class="col-md-2 col-sm-6">
									<div class="listRowInner">
										<span class="listTextStrong">Total Art.</span>
										<span class="listTextStrong"><span class="label label-success">'.$ItemTotal.'</span></span>
									</div>
								</div>
								<div class="col-md-2 hideMobile990">
									<div class="listRowInner">
										<span class="listTextStrong">Precio</span>
										<span class="listTextStrong"><span class="label label-info">'.$ItemPrice.'</span></span>
									</div>
								</div>
								'.$Stock.'
								
									
							</div>';
			}
			$Items .='</div>';
			
			// $Restrict	= $Row->Data['delivery_status']=='P' && $Row->Data['payment_status']=='P'? '':' undeleteable ';
			$Row->Data['number'] = Core::InvoiceNumber($Row->Data['number']);
			$Number = $Row->Data['prefix']>0? Core::InvoicePrefixNumber($Row->Data['prefix']).'-'.$Row->Data['number']:$Row->Data['number'];
			$Row->Data['entity'] = $Row->GetEntity();
			switch(strtolower($Mode))
			{
				case "list":
						// $Extra = !$Row->Data['extra']? '': '<div class="col-lg-2 col-md-3 col-sm-2 hideMobile990">
						// 				<div class="listRowInner">
						// 					<span class="emailTextResp">'.$Row->Data['extra'].'</span>
						// 				</div>
						// 			</div>';
									
					$RowBackground = $i % 2 == 0? '':' listRow2 ';
					
					$TotalCol =  '<div class="col-lg-2 col-md-3 col-sm-2 hideMobile990">
										<div class="listRowInner">
											<span class="listTextStrong">Total</span>
											<span class="emailTextResp"><span class="label label-success">'.$Row->Data['currency'].$Row->Data['total'].'</span></span>
										</div>
									</div>';
					
					$Regs	.= '<div class="row listRow'.$RowBackground.$Restrict.'" id="row_'.$Row->ID.'" title="una factura">
									<div class="col-lg-3 col-md-5 col-sm-8 col-xs-10">
										<div class="listRowInner">
											<img class="img-circle" style="border-radius:0%!important;" src="'.$Row->GetImg().'" alt="'.$Row->Data['name'].'">
											<span class="listTextStrong">'.$Number.'</span>
											<span class="smallDetails"><i class="fa fa-ticket"></i> '.$Row->Data['entity']['name'].'</span>
										</div>
									</div>
									<div class="col-lg-2 col-md-3 col-sm-2 hideMobile990">
										<div class="listRowInner">
											<span class="listTextStrong">Sub-Total</span>
											<span class="emailTextResp"><span class="label label-success">'.$Row->Data['currency'].$Row->Data['subtotal'].'</span></span>
										</div>
									</div>
									'.$TotalCol.'
									<div class="animated DetailedInformation Hidden col-md-12">
										'.$Items.'
									</div>
									<div class="listActions flex-justify-center Hidden">
										<div>'.$Actions.'</div>
									</div>
									
								</div>';
				break;
				case "grid":
				$Regs	.= '<li id="grid_'.$Row->ID.'" class="RoundItemSelect roundItemBig'.$Restrict.'" title="'.$Row->Data['name'].'">
						            <div class="flex-allCenter imgSelector">
						              <div class="imgSelectorInner">
						                <img src="'.$Row->GetImg().'" alt="'.$Row->Data['number'].'" class="img-responsive">
						                <div class="imgSelectorContent">
						                  <div class="roundItemBigActions">
						                    '.$Actions.'
						                    <span class="roundItemCheckDiv"><a href="#"><button type="button" class="btn roundBtnIconGreen Hidden" name="button"><i class="fa fa-check"></i></button></a></span>
						                  </div>
						                </div>
						              </div>
						              <div class="roundItemText">
						                <p><b>'.$Row->Data['number'].'</b></p>
						                <p>('.$Row->Data['currency'].$Row->Data['total'].')</p>
						              </div>
						            </div>
						          </li>';
				break;
			}
        }
        if(!$Regs)
        {
			switch ($_REQUEST['status']) {
				case 'A': $Regs.= '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron facturas pendiente de pago.</h4></div>'; break;
				case 'F': $Regs.= '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron facturas pagadas.</h4></div>'; break;
				default: $Regs.= '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron facturas pendiente de carga.</h4></div>'; break;
        	}
        }
		return $Regs;
	}
	
	protected function InsertSearchField()
	{
		return '<!-- Number -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="number" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','number','','form-control','placeholder="Nro. Factura"').'
          </div>
          <!-- From Total -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="from" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('select','from','','form-control','placeholder="Desde $"').'
          </div>
          <!-- To Total -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="to" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','to','','form-control','placeholder="Hasta $"').'
          </div>
          <!-- Due Date -->
          <div class="input-group">
            <span class="input-group-addon order-arrows sort-activated" order="due_date" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','due_date','','form-control delivery_date','placeholder="Vencimiento"').'
          </div>
          ';
	}
	
	protected function InsertSearchButtons()
	{
		if($_GET['status']=="P" || $_GET['status']=="Z")
		{
			$BtnText = 'Pedir Cotización';
			$BtnIcon = 'cart-plus';
		}else{
			$BtnText = 'Nueva Orden de Compra';	
			$BtnIcon = 'ambulance';
		}
		// $HTML =	'<!-- New Button --> 
		//     	<a href="new.php?status='.$_GET['status'].'"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-'.$BtnIcon.'"></i> '.$BtnText.'</button></a>
		//     	<!-- /New Button -->';
		return $HTML;
	}
	
	public function ConfigureSearchRequest()
	{
		$this->SetTable($this->Table.' a LEFT JOIN invoice_detail b ON (b.invoice_id=a.invoice_id) INNER JOIN invoice_operation c ON (c.operation_id=a.operation_id)');
		$this->SetFields('a.*,b.product_id,b.quantity,b.price,b.total as item_total');
		$this->SetWhere("a.organization_id=".$_SESSION['organization_id']);
		//$this->AddWhereString(" AND c.organization_id = a.organization_id");
		//$this->SetOrder('a.delivery_date');
		$this->SetGroupBy("a.".$this->TableID);
		
		foreach($_POST as $Key => $Value)
		{
			$_POST[$Key] = $Value;
		}
			
		if($_POST['from']) $this->SetWhereCondition("a.total",">=",$_POST['from']);
		if($_POST['to']) $this->SetWhereCondition("a.total","=<",$_POST['to']);
		if($_POST['number']) $this->SetWhereCondition("a.number","=",$_POST['agent']);
		// if($_POST['code']) $this->SetWhereCondition("c.code","LIKE","%".$_POST['code']."%");
		// if($_POST['extra']) $this->SetWhereCondition("a.extra","LIKE","%".$_POST['extra']."%");
		if($_POST['due_date']) $this->SetWhereCondition("a.due_date","=",$_POST['due_date']);
		
		
		if($_REQUEST['operation'])
		{
			if($_POST['operation']) $this->SetWhereCondition("a.operation_id","=", strtoupper($_POST['operation']));
			if($_GET['operation']) $this->SetWhereCondition("a.operation_id","=", strtoupper($_GET['operation']));	
		}
		
		if($_REQUEST['status'])
		{
			if($_POST['status']) $this->SetWhereCondition("a.status","=", strtoupper($_POST['status']));
			if($_GET['status']) $this->SetWhereCondition("a.status","=", strtoupper($_GET['status']));	
		}else{
			$this->SetWhereCondition("a.status","=","P");
		}
		
		if(strtolower($_POST['view_order_mode']))
			$Mode = $_POST['view_order_mode'];
		else
			$Mode = 'ASC';
		
		$Order = strtolower($_POST['view_order_field']);
		switch($Order)
		{
			case "name": 
				$Order = 'name';
				$Prefix = "d.";
			break;
			case "code": 
				$Order = 'code';
				$Prefix = "c.";
			break;
			case "agent": 
				$Order = 'name';
				$Prefix = "e.";
			break;
			default:
				$Order = 'due_date';
				$Prefix = "a.";		
			break;
		}
		$this->SetOrder($Prefix.$Order." ".$Mode);
		
		
		if($_POST['regsperview'])
		{
			$this->SetRegsPerView($_POST['regsperview']);
		}
		if(intval($_POST['view_page'])>0)
			$this->SetPage($_POST['view_page']);
	}

	public function MakeList()
	{
		return $this->MakeRegs("List");
	}

	public function MakeGrid()
	{
		return $this->MakeRegs("Grid");
	}

	public function GetData()
	{
		return $this->Data;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// PROCESS METHODS ///////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function Insert()
	{
		// ITEMS DATA
		$Items = array();
		for($I=1;$I<=$_POST['items'];$I++)
		{
			if($_POST['item_'.$I])
			{
				$ItemDate = implode("-",array_reverse(explode("/",$_POST['date_'.$I])));
				$Items[] = array('id'=>$_POST['item_'.$I],'price'=>$_POST['price_'.$I],'quantity'=>$_POST['quantity_'.$I], 'delivery_date'=>$ItemDate );
				if(!$Date)
				{
					$Date = $ItemDate;
				}
				if(strtotime($ItemDate." 00:00:00") > strtotime($Date." 00:00:00")){
					$Date = $ItemDate;
				}
			}
		}
		
		// Basic Data
		$Type			= $_POST['type'];
		$ProviderID		= $_POST['provider'];
		$AgentID 		= $_POST['agent']? $_POST['agent']: 0;
		$CurrencyID		= $_POST['currency'];
		$Extra			= $_POST['extra'];
		$Total			= $_POST['total_price'];
		$Admin			= new CoreUser($_SESSION['user_id']);
		$Status			= $_POST['status'];
		
		$NewID			= Core::Insert($this->Table,'type,provider_id,agent_id,currency_id,extra,total,delivery_date,status,creation_date,created_by,organization_id',"'".$Type."',".$ProviderID.",".$AgentID.",".$CurrencyID.",'".$Extra."',".$Total.",'".$Date."','".$Status."',NOW(),".$_SESSION['user_id'].",".$_SESSION['organization_id']);
		//echo $this->LastQuery();
		$New 	= new Invoice($NewID);
		
		// INSERT ITEMS
		foreach($Items as $Item)
		{
			if($Fields)
				$Fields .= "),(";
			$Fields .= $NewID.",".$ProviderID.",".$Item['id'].",".$Item['price'].",".$Item['quantity'].",'".$Item['delivery_date']."',".$CurrencyID.",NOW(),".$_SESSION['user_id'].",".$_SESSION['organization_id'];
		}
		Core::Insert('provider_order_item','order_id,provider_id,product_id,price,quantity,delivery_date,currency_id,creation_date,created_by,organization_id',$Fields);
		//echo $this->LastQuery();
		
	}
	
	public function Update()
	{
		$ID 	= $_POST['id'];
		$Edit	= new Invoice($ID);
		$Status = $Edit->Data['status'];
		if($Status!='P')
		{
			echo "403";
		}
		// ITEMS DATA
		$Items = array();
		for($I=1;$I<=$_POST['items'];$I++)
		{
			if($_POST['item_'.$I])
			{
				$ItemDate = implode("-",array_reverse(explode("/",$_POST['date_'.$I])));
				$Items[] = array('id'=>$_POST['item_'.$I],'price'=>$_POST['price_'.$I],'quantity'=>$_POST['quantity_'.$I], 'delivery_date'=>$ItemDate );
				if(!$Date)
				{
					$Date = $ItemDate;
				}
				if(strtotime($ItemDate." 00:00:00") > strtotime($Date." 00:00:00")){
					$Date = $ItemDate;
				}
			}
		}
		
		// Basic Data
		$Type			= $_POST['type'];
		$ProviderID		= $_POST['provider'];
		$AgentID 		= $_POST['agent']? $_POST['agent']: 0;
		$CurrencyID		= $_POST['currency'];
		$Extra			= $_POST['extra'];
		$Total			= $_POST['total_price'];
		
		// CREATE NEW IMAGE IF EXISTS
		if($Image!=$Edit->Data['logo'])
		{
			if($Image!=$Edit->GetDefaultImg())
			{
				if(file_exists($Edit->GetImg()))
					unlink($Edit->GetImg());
				$Dir 	= array_reverse(explode("/",$Image));
				$Temp 	= $Image;
				$Image 	= $Edit->ImgGalDir().$Dir[0];
				copy($Temp,$Image);
			}
		}
		
		$Update		= Core::Update('provider_order',"type='".$Type."',provider_id=".$ProviderID.",agent_id=".$AgentID.",currency_id=".$CurrencyID.",delivery_date='".$Date."',extra='".$Extra."',total=".$Total.",updated_by=".$_SESSION['user_id'],"order_id=".$ID);
		//echo $this->LastQuery();
		
		// DELETE OLD ITEMS
		Core::Delete('provider_order_item',"order_id = ".$ID);
		
		// INSERT ITEMS
		foreach($Items as $Item)
		{
			if($Fields)
				$Fields .= "),(";
			$Fields .= $ID.",".$ProviderID.",".$Item['id'].",".$Item['price'].",".$Item['quantity'].",'".$Item['delivery_date']."',".$CurrencyID.",NOW(),".$_SESSION['user_id'].",".$_SESSION['organization_id'];
		}
		Core::Insert('provider_order_item','order_id,provider_id,product_id,price,quantity,delivery_date,currency_id,creation_date,created_by,organization_id',$Fields);
	}
	
	public function Activate()
	{
		$ID	= $_POST['id'];
		$Order = new Invoice($ID);
		$Status = $Order->Data['status'] == 'I'? 'P' : 'A';
		Core::Update($this->Table,"status = '".$Status."'",$this->TableID."=".$ID);
	}
	
	public function Store()
	{
		$ID	= $_POST['id'];
		Core::Update($this->Table,"status = 'Z'",$this->TableID."=".$ID);
	}
	
	// public function Generateinvoice()
	// {
	// 	$ID	= $_POST['id'];
	// 	$ProviderID = $_POST['provider'];
	// 	$Currency = $_POST['currency'];
	// 	$Invoice = $_POST['invoice_number'];
	// 	$SubTotal = $_POST['total'];
		
		
	// 	$InvoiceID = Core::Insert('invoice','entity_id,currency_id,subtotal,number,operation,status,creation_date,created_by,organization_id',$ProviderID.",".$Currency.",".$SubTotal.",".$Invoice.",'I','P',NOW(),".$_SESSION['user_id'].",".$_SESSION['organization_id']);
	// 	//echo $this->LastQuery()." *****/";
		
	// 	// ITEMS DATA
	// 	$Items = array();
	// 	for($I=1;$I<=$_POST['total_items'];$I++)
	// 	{
	// 		if($_POST['id'.$I])
	// 		{
	// 			$Separator = $I>1? "),(":"";
					
	// 			$QueryFields .= $Separator.$InvoiceID.",".$_POST['product'.$I].",'".$_POST['description'.$I]."',".$_POST['quantity_'.$I].",".$_POST['price'.$I].",".$_POST['total'.$I].",NOW(),".$_SESSION['user_id'].",".$_SESSION['organization_id'];
	// 			//$Items[] = array('id'=>$_POST['product'.$I],'description'=>$_POST['description'.$I],'price'=>$_POST['price'.$I],'quantity'=>$_POST['quantity_'.$I], 'total'=>$_POST['total'.$I]);
	// 			$QuantityPaid = $_POST['quantity_'.$I] + $_POST['quantity_paid'.$I];
	// 			Core::Update("provider_order_item","payment_status='".$Status."', quantity_paid=".$QuantityPaid,"item_id=".$_POST['item'.$I]);
	// 			//echo "----".$this->LastQuery()."----";
	// 		}
	// 	}
	// 	Core::Insert('invoice_detail','invoice_id,product_id,description,quantity,price,total,creation_date,created_by,organization_id',$QueryFields);
		
	// 	$OrderStatus = Core::Select($this->Table.'_item',"SUM(quantity_paid) AS quantity_paid,SUM(quantity) AS quantity",$this->TableID."=".$ID);
	// 	$OrderStatus = $OrderStatus[0];
	// 	$Status = $OrderStatus['quantity']==$OrderStatus['quantity_paid']? 'F':'A';
	// 	// $Order = Core::Select($this->Table,"payment_status,delivery_status",$this->TableID."=".$ID);
	// 	// $Order = $Order[0];
	// 	// $Upadte = $Order['payment_status']=='F' && $Order['delivery_status']=='F'? ",status='F'":"";
	// 	Core::Update($this->Table,"payment_status='".$Status."'".$Upadte,$this->TableID."=".$ID);
		
	// 	Core::Insert('relation_invoice_order','invoice_id,order_id,amount',$InvoiceID.",".$ID.",".$SubTotal);
	// 	//echo $this->LastQuery()." \\n\\n";
	// }
	
	public function Delete()
	{
		$ID			= $_POST['id'];
		$Order		= new ProviderOrder();
		$Order->Degenerateinvoice($ID);
	}
	
	public function Search()
	{
		$this->ConfigureSearchRequest();
		echo $this->InsertSearchResults();
	}
	
	public function Newimage()
	{
		if(count($_FILES['image'])>0)
		{
			$ID	= $_POST['id'];
			if($ID)
			{
				$New = new Invoice($ID);
				if($_POST['newimage']!=$New->GetImg() && file_exists($_POST['newimage']))
					unlink($_POST['newimage']);
				$TempDir= $this->ImgGalDir;
				$Name	= "provider".intval(rand()*rand()/rand());
				$Img	= new CoreFileData($_FILES['image'],$TempDir,$Name);
				echo $Img	-> BuildImage(100,100);
			}else{	
				if($_POST['newimage']!=$this->GetDefaultImg() && file_exists($_POST['newimage']))
					unlink($_POST['newimage']);
				$TempDir= $this->ImgGalDir;
				$Name	= "provider".intval(rand()*rand()/rand());
				$Img	= new CoreFileData($_FILES['image'],$TempDir,$Name);
				echo $Img	-> BuildImage(100,100);
			}
		}
	}
	
	public function Validate()
	{
		$User 			= strtolower($_POST['name']);
		$ActualUser 	= strtolower($_POST['actualname']);

	    if($ActualUser)
	    	$TotalRegs  = Core::NumRows($this->Table,'*',"name = '".$User."' AND name <> '".$ActualUser."'");
    	else
		    $TotalRegs  = Core::NumRows($this->Table,'*',"name = '".$User."'");
		if($TotalRegs>0) echo $TotalRegs;
	}
	
	public function Fillagents()
	{
		$Provider = $_POST['provider'];
		$Agents = Core::Select('provider_agent','agent_id,name',"provider_id=".$Provider,'name');
		if(count($Agents)>0)
		{
			$HTML = Core::InsertElement('select','agent','','form-control chosenSelect','data-placeholder="Seleccione un Contacto"',$Agents,' ','');
		}else{
			
		}
		echo $HTML;
	}
	
	// public function Addorderitem()
	// {
	// 	$ID = $_POST['item'];
	// 	$TotalPrice = "$ 0.00";
	// 	if($ID % 2 != 0)
	// 		$BgClass = "bg-gray";
	// 	else
	// 		$BgClass = "bg-gray-active";
	// 	$HTML = '
	// 		<div id="item_row_'.$ID.'" item="'.$ID.'" class="row form-group inline-form-custom ItemRow '.$BgClass.'" style="margin-bottom:0px!important;padding:10px 0px!important;">
 //               <form id="item_form_'.$ID.'">
 //               <div class="col-xs-4 txC">
 //               	<span id="Item'.$ID.'" class="Hidden ItemText'.$ID.'"></span>
 //                 '.Core::InsertElement('select','item_'.$ID,'','ItemField'.$ID.' form-control chosenSelect','validateEmpty="Seleccione un Art&iacute;culo" data-placeholder="Seleccione un Art&iacute;culo"',Core::Select('product','product_id,code',"status='A' AND organization_id=".$_SESSION['organization_id'],'code'),' ','').'
 //               </div>
 //               <div class="col-xs-1 txC">
 //               	<span id="Price'.$ID.'" class="Hidden ItemText'.$ID.'"></span>
 //                 '.Core::InsertElement('text','price_'.$ID,'','ItemField'.$ID.' form-control calcable','data-inputmask="\'mask\': \'9{+}.99\'" placeholder="Precio" validateEmpty="Ingrese un precio"').'
 //               </div>
 //               <div class="col-xs-1 txC">
 //               	<span id="Quantity'.$ID.'" class="Hidden ItemText'.$ID.'"></span>
 //                 '.Core::InsertElement('text','quantity_'.$ID,'','ItemField'.$ID.' form-control calcable QuantityItem','data-inputmask="\'mask\': \'9{+}\'" placeholder="Cantidad" validateEmpty="Ingrese una cantidad"').'
 //               </div>
 //               <div class="col-xs-2 txC">
 //                 <span id="Date'.$ID.'" class="Hidden ItemText'.$ID.' OrderDate"></span>
 //                 '.Core::InsertElement('text','date_'.$ID,'','ItemField'.$ID.' form-control delivery_date','placeholder="Fecha de Entrega" validateEmpty="Ingrese una fecha"').'
 //               </div>
 //               <div  id="item_number_'.$ID.'" class="col-xs-1 txC item_number" total="0" item="'.$ID.'">'.$TotalPrice.'</div>
 //               <div class="col-xs-3 txC">
	// 			  <button type="button" id="SaveItem'.$ID.'" class="btn btnGreen SaveItem" style="margin:0px;" item="'.$ID.'"><i class="fa fa-check"></i></button>
	// 			  <button type="button" id="EditItem'.$ID.'" class="btn btnBlue EditItem Hidden" style="margin:0px;" item="'.$ID.'"><i class="fa fa-pencil"></i></button>
	// 			  <button type="button" id="DeleteItem'.$ID.'" class="btn btnRed DeleteItem" style="margin:0px;" item="'.$ID.'"><i class="fa fa-trash"></i></button>
	// 			</div>
	// 			</form>
 //           </div>';
 //           echo $HTML;
	// }
}
?>
