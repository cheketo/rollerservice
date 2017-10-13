<?php
class Category
{
	use CoreSearchList,CoreCrud,CoreImage;
	
	var $Products			= array();
	const TABLE				= 'product_category';
	const TABLE_ID			= 'category_id';
	const SEARCH_TABLE		= 'view_category_list';
	const DEFAULT_IMG		= '../../../../skin/images/categories/default/default.jpg';
	const DEFAULT_IMG_DIR	= '../../../../skin/images/categories/default/';
	const IMG_DIR			= '../../../../skin/images/categories/';
	
	public function __construct($ID=0)
	{
		$this->ID = $ID;
		$this->GetData();
		self::SetImg($this->Data['img']);
		// $this->Data['parent'] = $this->GetParent();
		// if($this->Data['parent_id'])
		// {
		// 	$Parent = Core::Select($this->Table,"title","category_id=".$this->Data['parent_id']);
		// 	$this->Data['parent'] = $Parent[0]['title'];
		// }
		
	}
	
	public function GetProducts()
	{
		if(!$this->Products)
		{
			$this->Products = Core::Select(Product::TABLE." a INNER JOIN ".Brand::TABLE." b ON (a.".Brand::TABLE_ID."=b.".Brand::TABLE_ID.")",'a.*,b.name AS brand',self::TABLE_ID."=".$this->ID);
		}
		return $this->Products;
		
	}
	
	// public function GetParent()
	// {
	// 	if(!$this->Parent)
	// 	{
	// 		$this->Parent = Core::Select(self::TABLE,'*',self::TABLE_ID."=".$this->Data['parent_id'])[0];
	// 	}
	// 	return $this->Parent;
		
	// }
	
	public static function GetParentsArray($Order='title')
	{
		$Parents = array();
		$Categories = Core::Select(self::TABLE,self::TABLE_ID.',title',"status='A' AND ".CoreOrganization::TABLE_ID." = ".$_SESSION[CoreOrganization::TABLE_ID],$Order);
		foreach($Categories as $Category)
		{
			$Children = Core::Select(self::TABLE,self::TABLE_ID,"status='A' AND parent_id =".$Category[self::TABLE_ID]);
			if(count($Children)>0)
				$Parents[] = array(self::TABLE_ID => $Category[self::TABLE_ID], 'title' => $Category['title']);
		}
		return $Parents;
	}
	
	public static function GetAllCategories($Order='parent_id,title')
	{
		return Core::Select(self::TABLE,'*',"status='A' AND ".CoreOrganization::TABLE_ID." = ".$_SESSION[CoreOrganization::TABLE_ID],$Order);
		
	}
	
	public static function CalculateCategoryLevel($CategoryID)
	{
		$Parent = Core::Select(self::TABLE,"parent_id",self::TABLE_ID."=".$CategoryID);
		$ParentID = $Parent[0]['parent_id'];
		if($ParentID>0)
		{
			return 1 + self::CalculateCategoryLevel($ParentID);
		}else{
			return 1;
		}
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
			$HTML	.= Core::InsertElement('hidden','delete_question_'.$Object->ID,'&iquest;Desea eliminar la l&iacute;nea <b>'.$Object->Data['title'].'</b> ?');
			$HTML	.= Core::InsertElement('hidden','delete_text_ok_'.$Object->ID,'La l&iacute;nea <b>'.$Object->Data['title'].'</b> ha sido eliminada.');
			$HTML	.= Core::InsertElement('hidden','delete_text_error_'.$Object->ID,'Hubo un error al intentar eliminar la l&iacute;nea <b>'.$Object->Data['title'].'</b>.');
			
		}else{
			$HTML	.= '<a class="activateElement hint--bottom hint--bounce hint--success" aria-label="Activar" process="'.PROCESS.'" id="activate_'.$Object->ID.'"><button type="button" class="btn btnGreen"><i class="fa fa-check-circle"></i></button></a>';
			$HTML	.= Core::InsertElement('hidden','activate_question_'.$Object->ID,'&iquest;Desea activar la l&iacute;nea <b>'.$Object->Data['title'].'</b> ?');
			$HTML	.= Core::InsertElement('hidden','activate_text_ok_'.$Object->ID,'La l&iacute;nea <b>'.$Object->Data['title'].'</b> ha sido activada.');
			$HTML	.= Core::InsertElement('hidden','activate_text_error_'.$Object->ID,'Hubo un error al intentar activar la l&iacute;nea <b>'.$Object->Data['title'].'</b>.');
		}
		return $HTML;
	}
	
	protected static function MakeListHTML($Object)
	{
		$HTML = '<div class="col-lg-4 col-md-5 col-sm-4 col-xs-10">
					<div class="listRowInner">
						<img class="img-circle" src="'.$Object->Img.'" alt="'.$Object->Data['title'].'">
						<span class="listTextStrong">'.$Object->Data['title'].'</span>
					</div>
				</div>
				<div class="col-lg-2 col-md-3 col-sm-3 hideMobile990">
					<div class="listRowInner">
						<span class="smallTitle">Nombre corto</span>
						<span class="listTextStrong">'.ucfirst($Object->Data['short_title']).'</span>
					</div>
				</div>
				<div class="col-lg-4 col-md-3 col-sm-4 hideMobile990">
					<div class="listRowInner">
						<span class="smallTitle">Ubicaci&oacute;n</span>
						<span class="listTextStrong">'.ucfirst($Object->Data['parent']).'</span>
					</div>
				</div>
				';
		return $HTML;
	}
	
	protected static function MakeItemsListHTML($Object)
	{
		$Products = $Object->GetProducts();
		$HTML .= '
					<div class="row bg-gray showMobile990" style="padding:5px;">
						<div class="col-xs-12">
							<div class="listRowInner">
								<span class="itemRowtitle">
									<span class="listTextStrong">Nombre corto</span> 
									<span class="label label-primary">'.ucfirst($Object->Data['short_title']).'</span>
								</span>
							</div>
						</div>
					</div>';
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
									<span class="listTextStrong">Marca</span>
									<span class="label label-warning">'.$Item['brand'].'</span>
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
		                <img src="'.$Object->Img.'" alt="'.$Object->Data['title'].'" class="img-responsive">
		                <div class="imgSelectorContent">
		                  <div class="roundItemBigActions">
		                    '.$ButtonsHTML.'
		                    <span class="roundItemCheckDiv"><a href="#"><button type="button" class="btn roundBtnIconGreen Hidden" name="button"><i class="fa fa-check"></i></button></a></span>
		                  </div>
		                </div>
		              </div>
		              <div class="roundItemText">
		                <p><b>'.$Object->Data['title'].'</b></p>
		                <p>('.ucfirst($Object->Data['short_title']).')</p>
		              </div>
		            </div>';
		return $HTML;
	}
	
	public static function MakeNoRegsHTML()
	{
		return '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron l&iacute;neas.</h4><p>Puede crear una nueva l&iacute;nea haciendo click <a href="new.php">aqui</a>.</p></div>';	
	}
	
	protected function SetSearchFields()
	{
		$Parents = array();
		$Parents[] = array(self::TABLE_ID=>"0","title"=>"L&iacute;nea Principal");
		$ParentsArray = self::GetParentsArray();
		foreach($ParentsArray as $Parent)
		{
			$Parents[] = array(self::TABLE_ID=>$Parent[self::TABLE_ID],'title'=>$Parent['title']);
		}
		$this->SearchFields['title'] = Core::InsertElement('text','title','','form-control','placeholder="Nombre"');
		$this->SearchFields['short_title'] = Core::InsertElement('text','short_title','','form-control','placeholder="Nombre Corto"');
		$this->SearchFields['code'] = Core::InsertElement('text','code','','form-control','placeholder="Producto"');
		$this->SearchFields['brand'] = Core::InsertElement('text','brand','','form-control','placeholder="Marca"');
		$this->SearchFields['parent_id'] = Core::InsertElement('select','parent_id','','form-control chosenSelect','',$Parents,'','Cualquier Ubicaci&oacute;n');
	}
	
	protected function InsertSearchButtons()
	{
		return '<a href="new.php" class="hint--bottom hint--bounce hint--success" aria-label="Nueva L&iacute;nea"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-plus-square"></i></button></a>';
	}
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// PROCESS METHODS ///////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function Insert()
	{
		$Parent 	= $_POST['parent']? $_POST['parent'] : 0;
		$Insert		= Core::Insert(self::TABLE,'title,short_title,parent_id,creation_date,'.CorOrganization::TABLE_ID.',created_by',"'".$_POST['title']."','".$_POST['short_title']."',".$Parent.",NOW(),".$_SESSION[CorOrganization::TABLE_ID].",".$_SESSION[CoreUser::TABLE_ID]);
		//echo $this->LastQuery();
	}	
	
	public function Update()
	{
		$ID 		= $_POST['id'];
		$Parent 	= $_POST['parent']? $_POST['parent'] : 0;
		$Update		= Core::Update(self::TABLE,"title='".$_POST['title']."',short_title='".$_POST['short_title']."',parent_id=".$Parent.",updated_by=".$_SESSION[CoreUser::TABLE_ID],self::TABLE_ID."=".$ID);
		//echo $this->LastQuery();
	}
	
	public function Validate()
	{
		echo self::ValidateValue('name',$_POST['title'],$_POST['titlename'],'parent_id',$_POST['parent']);
	}
}
?>
