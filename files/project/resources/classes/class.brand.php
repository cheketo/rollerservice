<?php

class Brand 
{
	use CoreSearchList,CoreCrud,CoreImage;
	
	var $Products			= array();
	const TABLE				= 'product_brand';
	const TABLE_ID			= 'brand_id';
	const SEARCH_TABLE		= 'view_brand_list';
	const DEFAULT_IMG		= '../../../../skin/images/brands/default/default.png';
	const DEFAULT_IMG_DIR	= '../../../../skin/images/brands/default/';
	const IMG_DIR			= '../../../../skin/images/brands/';

	public function __construct($ID=0)
	{	
		$this->ID = $ID;
		$this->GetData();
		self::SetImg($this->Data['img']);
	}
	
	public function GetProducts()
	{
		if(empty($this->Products))
		{
			$this->Products = Core::Select(Product::TABLE." a INNER JOIN ".Category::TABLE." b ON (a.".Category::TABLE_ID."=b.".Category::TABLE_ID.")",'a.*,b.title AS category',self::TABLE_ID."=".$this->ID);
		}
		return $this->Products;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// SEARCHLIST FUNCTIONS ///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	protected static function MakeActionButtonsHTML($Object,$Mode='list')
	{
		if($Mode!='grid') $HTML .=	'<a class="hint--bottom hint--bounce" aria-label="M&aacute;s informaci&oacute;n"><button type="button" class="btn bg-navy ExpandButton" id="expand_'.$Object->ID.'"><i class="fa fa-plus"></i></button></a> ';;
		$HTML	.= 	'<a href="edit.php?id='.$Object->ID.'" class="hint--bottom hint--bounce hint--info" aria-label="Editar"><button type="button" class="btn btnBlue"><i class="fa fa-pencil"></i></button></a>';
		if($Object->Data['status']=="A")
		{
			$HTML	.= '<a class="deleteElement hint--bottom hint--bounce hint--error" aria-label="Eliminar" process="'.PROCESS.'" id="delete_'.$Object->ID.'"><button type="button" class="btn btnRed"><i class="fa fa-trash"></i></button></a>';
			$HTML	.= Core::InsertElement('hidden','delete_question_'.$Object->ID,'&iquest;Desea eliminar la marca <b>'.$Object->Data['name'].'</b> ?');
			$HTML	.= Core::InsertElement('hidden','delete_text_ok_'.$Object->ID,'La marca <b>'.$Object->Data['name'].'</b> ha sido eliminada.');
			$HTML	.= Core::InsertElement('hidden','delete_text_error_'.$Object->ID,'Hubo un error al intentar eliminar la marca <b>'.$Object->Data['name'].'</b>.');
			
		}else{
			$HTML	.= '<a class="activateElement hint--bottom hint--bounce hint--success" aria-label="Activar" process="'.PROCESS.'" id="activate_'.$Object->ID.'"><button type="button" class="btn btnGreen"><i class="fa fa-check-circle"></i></button></a>';
			$HTML	.= Core::InsertElement('hidden','activate_question_'.$Object->ID,'&iquest;Desea activar la marca <b>'.$Object->Data['name'].'</b> ?');
			$HTML	.= Core::InsertElement('hidden','activate_text_ok_'.$Object->ID,'La marca <b>'.$Object->Data['name'].'</b> ha sido activada.');
			$HTML	.= Core::InsertElement('hidden','activate_text_error_'.$Object->ID,'Hubo un error al intentar activar la marca <b>'.$Object->Data['name'].'</b>.');
		}
		return $HTML;
	}
	
	protected static function MakeListHTML($Object)
	{
		$HTML = '<div class="col-lg-4 col-md-5 col-sm-5 col-xs-10">
			<div class="listRowInner">
				<img class="img-circle" src="'.$Object->Img.'" alt="'.$Object->Data['name'].'">
				<span class="listTextStrong">'.$Object->Data['name'].'</span>
			</div>
		</div>';
		return $HTML;
	}
	
	protected static function MakeItemsListHTML($Object)
	{
		$Products = $Object->GetProducts();
		foreach($Products as $Item)
		{
			$RowClass = $RowClass != 'bg-gray'? 'bg-gray':'bg-gray-active';
			$HTML .= '
						<div class="row '.$RowClass.'" style="padding:5px;">
							<div class="col-xs-6">
								<div class="listRowInner">
									<span class="itemRowtitle">
										<span class="listTextStrong">Producto</span> 
										<span class="label label-primary">'.$Item['code'].'</span>
									</span>
								</div>
							</div>
							<div class="col-xs-6">
								<div class="listRowInner">
									<span class="listTextStrong">Categor&iacute;a</span>
									<span class="label label-warning">'.$Item['category'].'</span>
								</div>
							</div>
						</div>';
		}
		if(!$HTML) $HTML='<div class="bg-gray txC" style="padding:5px;">Sin productos asociados</div>';
		return $HTML;
	}
	
	protected static function MakeGridHTML($Object)
	{
		$ButtonsHTML = '<span class="roundItemActionsGroup">'.self::MakeActionButtonsHTML($Object,'grid').'</span>';
		$HTML = '<div class="flex-allCenter imgSelector">
		              <div class="imgSelectorInner">
		                <img src="'.$Object->Img.'" alt="'.$Object->Data['name'].'" class="img-responsive">
		                <div class="imgSelectorContent">
		                  <div class="roundItemBigActions">
		                    '.$ButtonsHTML.'
		                    <span class="roundItemCheckDiv"><a href="#"><button type="button" class="btn roundBtnIconGreen Hidden" name="button"><i class="fa fa-check"></i></button></a></span>
		                  </div>
		                </div>
		              </div>
		              <div class="roundItemText">
		                <p><b>'.$Object->Data['name'].'</b></p>
		              </div>
		            </div>';
		return $HTML;
	}
	
	public static function MakeNoRegsHTML()
	{
		return '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron marcas.</h4><p>Puede crear una nueva marca haciendo click <a href="new.php">aqui</a>.</p></div>';	
	}
	
	protected function SetSearchFields()
	{
		$this->SearchFields['name'] = Core::InsertElement('text','name','','form-control','placeholder="Nombre"');
		$this->SearchFields['code'] = Core::InsertElement('text','code','','form-control','placeholder="Producto"');
		$this->SearchFields['category'] = Core::InsertElement('text','category','','form-control','placeholder="L&iacute;nea"');	
	}
	
	protected function InsertSearchButtons()
	{
		return '<a href="new.php" class="hint--bottom hint--bounce hint--success" aria-label="Nueva Marca"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-plus-square"></i></button></a>';
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// PROCESS METHODS ///////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function Insert()
	{
		Core::Insert(self::TABLE,'name,creation_date,'.CoreOrganization::TABLE_ID,"'".$_POST['name']."',NOW(),".$_SESSION[CoreOrganization::TABLE_ID]);
	}	
	
	public function Update()
	{
		Core::Update(self::TABLE,"name='".$_POST['name']."'",self::TABLE_ID."=".$_POST['id']);
		// echo Core::LastQuery();
	}
	
	public function Validate()
	{
		echo self::ValidateValue('name',$_POST['name'],$_POST['actualname']);
	}
}
?>