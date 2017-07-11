<?php
trait CoreSearchList
{
    var $Where = '1=1';
	var $Regs = array();
	var $TotalRegs;
	var $Page = 1;
	var $RegsPerView = 25;
	var $Order;
	var $SearchTable;
	var $Fields = '*';
	
    public function GetWhere()
	{
		return $this->Where;
	}
	
	public function SetWhereCondition($Key="",$Operation="=",$Value="",$Connector="AND")
	{
		if(isset($Key))
		{
			switch(strtoupper($Operation))
			{
				case 'LIKE': 
					$Value = "'%".$Value."%'";
				break;
				case 'IN':
					$Value = "(".$Value.")";
				break;
				default:
					$Value = "'".$Value."'";
				break;
			}
			$this->Where .= " ".$Connector." ".$Key." ".$Operation." ".$Value."";
			return $this->GetWhere();	
		}
	}
	
	public function AddWhereString($String="")
	{
		$this->Where .= $String;
		return $this->GetWhere();
	}
	
	public function SetWhere($Where="")
	{
		$this->Where = $Where;
		return $this->GetWhere();
	}
	
	public function SetRegsPerView($Regs)
	{
		$this->RegsPerView = $Regs;
	}
	
	public function GetRegsPerView()
	{
		return $this->RegsPerView;
	}
	
	public function GetRegs()
	{
		if(!$this->Regs)
		{
			$this->Regs = Core::Select($this->GetTable(),$this->GetFields(),$this->GetWhere(),$this->GetOrder(),$this->GetGroupBy(),$this->GetLimit());
			
		}
		return $this->Regs;
	}
	
	public function GetTotalRegs()
	{
		if($this->TotalRegs)
			return $this->TotalRegs;
		else
			return "0";
	}
	
	public function CalculateTotalRegs()
	{
		$this->TotalRegs = Core::NumRows($this->GetTable(),$this->GetFields(),$this->GetWhere(),$this->GetOrder(),$this->GetGroupBy());
		if($this->TotalRegs)
			return $this->TotalRegs;
		else
			return "0";
	}
	
	public function SetPage($Page)
	{
		$this->Page = $Page;
	}
	
	public function GetPage()
	{
		return $this->Page;
	}
	
	public function SetOrder($Order)
	{
		$this->Order = $Order;
	}
	
	public function GetOrder()
	{
		return $this->Order;
	}
	
	public function SetGroupBy($GroupBy)
	{
		$this->GroupBy = $GroupBy;
	}
	
	public function GetGroupBy()
	{
		return $this->GroupBy;
	}
	
	public function SetTable($Table)
	{
		$this->SearchTable = $Table;
	}
	
	public function GetTable()
	{
		return $this->SearchTable;
	}
	
	public function SetFields($Fields)
	{
		$this->Fields = $Fields;
	}
	
	public function GetFields()
	{
		return $this->Fields;
	}
	
	public function GetTotalPages()
	{
		$Total			= $this->GetTotalRegs();
		$RegsPerView	= $this->GetRegsPerView();
		if($RegPerView>=$Total || $RegsPerView<=0)
		{
			return 0;
		}else{
			return intval(ceil($Total/$RegsPerView)); 	
		}
		
	}
	
	public function GetLimit()
	{
		$TotalRegs	= $this->CalculateTotalRegs();
		$TotalPages	= $this->GetTotalPages();
		$Page		= $this->GetPage();
		$RegPerView	= $this->GetRegsPerView();
		
		if($Page<=$TotalPages)
		{
			$From = $RegPerView * ($Page-1);
			$To = $RegPerView;
		}
		else
		{
			$From = 0;
			$To = $TotalRegs;
		}
		return $From.", ".$To;
	}
	
	public function InsertSearchList()
	{
		return '<div class="box">
			<div class="box-header with-border">
				<!-- Search Filters -->
		    	<div class="SearchFilters searchFiltersHorizontal animated fadeIn Hidden" style="margin-bottom:10px;">
			        <div class="form-inline" id="SearchFieldsForm">
			        	'.Core::InsertElement('hidden','view_type','list').'
			        	'.Core::InsertElement('hidden','view_page','1').'
			        	'.Core::InsertElement('hidden','view_order_field',$this->GetOrder()).'
			        	'.Core::InsertElement('hidden','view_order_mode','asc').'
			        	'.$this->InsertSearchField().'
			          <!-- Submit Button -->
			          <button type="button" class="btn btnGreen searchButton">Buscar</button>
			          <button type="button" class="btn btnGrey" id="ClearSearchFields">Limpiar</button>
			          <!-- Decoration Arrow -->
			          <div class="arrow-right-border">
			            <div class="arrow-right-sf"></div>
			          </div>
			        </div>
			      </div>
			      <!-- /Search Filters -->
    			'.$this->InsertDefaultSearchButtons().$this->InsertSearchButtons().'
			      '.Core::InsertElement('hidden','selected_ids','').'
			      <div class="changeView">
			        <button aria-label="Buscar" class="ShowFilters SearchElement btn hint--bottom hint--bounce"><i class="fa fa-search"></i></button>
			        <button aria-label="Ver listado" class="ShowList GridElement btn Hidden hint--bottom-left hint--bounce"><i class="fa fa-list"></i></button>
			        <button aria-label="Ver grilla" class="ShowGrid ListElement btn hint--bottom-left hint--bounce"><i class="fa fa-th-large"></i></button>
			      </div>
			</div>
			<!-- /.box-header -->
    		<div class="box-body">
			      '.$this->InsertSearchResults().'
			    </div><!-- /.box-body -->
			    <div class="box-footer clearfix">
			      <!-- Paginator -->
			      <div class="form-inline paginationLeft">
			    	<div class="row">
			    		<div class="col-xs-12 col-sm-4">
					    	<div class="row">
					    		<div class="col-xs-6 col-sm-3" style="margin:0px;padding:0px;margin-top:7px;">
					    			<span class="pull-right">Mostrando&nbsp;</span>
					    		</div>
					    		<div class="col-xs-4  col-sm-2 txC" style="margin:0px;padding:0px;">
					    			'.Core::InsertElement('select','regsperview',$this->GetRegsPerView(),'form-control chosenSelect','',array("5"=>"5","10"=>"10","25"=>"25","50"=>"50","100"=>"100")).'
					    		</div>
					    		<div class="col-xs-2  col-sm-2" style="margin:0px;padding:0px;margin-top:7px;">
					    			&nbsp;de <b><span id="TotalRegs">'.$this->GetTotalRegs().'</span></b>
					    		</div>
					    	</div>
				    	</div>
				    	<div class="col-xs-12 col-sm-8">
				    		<ul class="paginationRight pagination no-margin pull-right">
				    		</ul>
				    	</div>
				    </div>
			          
			          
			          
			      </div>
			      
			      <!-- Paginator -->
			    </div>
			  </div><!-- /.box -->
			  ';
	}
	
	public function InsertSearchResults()
	{
		if($_POST['view_type']=='grid')
			$ListClass = 'Hidden';
		else
			$GridClass = 'Hidden';
			
		return '<div class="contentContainer txC" id="SearchResult" object="'.get_class ($this).'"><!-- List Container -->
			        <div class="GridView row horizontal-list flex-justify-center GridElement '.$GridClass.' animated fadeIn">
			          <ul>
			            '.$this->MakeGrid().'
			          </ul>
			        </div><!-- /.horizontal-list -->
			        <div class="row ListView ListElement animated fadeIn '.$ListClass.'">
			          <div class="container-fluid">
			            '.$this->MakeList().'
			          </div><!-- container-fluid -->
			        </div><!-- row -->
			        '.Core::InsertElement('hidden','totalregs',$this->GetTotalRegs()).'
			      </div><!-- /Content Container -->';
	}
	
	public function InsertDefaultSearchButtons()
	{
		return '<!-- Select All -->
		    	<button aria-label="Seleccionar todos" type="button" id="SelectAll" class="btn animated fadeIn NewElementButton hint--bottom-right hint--bounce"><i class="fa fa-square-o"></i></button>
		    	<button type="button" aria-label="Deseleccionar todos" id="UnselectAll" class="btn animated fadeIn NewElementButton Hidden hint--bottom-right hint--bounce"><i class="fa fa-square"></i></button>
		    	<!--/Select All -->
		    	<!-- Remove All -->
		    	<button type="button" aria-label="Eliminar Seleccionados" title="Borrar registros seleccionados" class="btn bg-red animated fadeIn NewElementButton Hidden DeleteSelectedElements hint--bottom hint--bounce hint--error"><i class="fa fa-trash-o"></i></button>
		    	<!-- /Remove All -->
		    	<!-- Activate All -->
		    	<button type="button" aria-label="Activar Seleccionados" class="btn btnGreen animated fadeIn NewElementButton Hidden ActivateSelectedElements hint--bottom hint--bounce hint--success"><i class="fa fa-check-circle"></i></button>
		    	<!-- /Activate All -->
		    	';
	}
	
	public function Search()
	{
		$this->ConfigureSearchRequest();
		echo $this->InsertSearchResults();
	}
	
	public function SetSearchRequest($Fields=array(),$Order='',$Mode='ASC',$Regs='',$Page='')
	{
		$this->SetTable(self::SEARCH_TABLE);
		$this->SetFields('*');
		
		foreach($Fields as $Field => $Config)
		{
			if(!$Config['condition']) $Config['condition'] = 'LIKE';
			$this->SetWhereCondition($Field,$Config['condition'],$Config['value']);
		}
		
		if($Order)
			$this->SetOrder($Order." ".$Mode);

		if(intval($Regs)>0)
			$this->SetRegsPerView($Reg);
		if(intval($Page)>0)
			$this->SetPage($Page);
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
}
?>