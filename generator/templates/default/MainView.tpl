<?php
/**
 * Note : Code is released under the GNU LGPL
 *
 * Please do not change the header of this file 
 *
 * This library is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * Lesser General Public License as published by the Free Software Foundation; either version 2 of 
 * the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 *
 * See the GNU Lesser General Public License for more details.
 */

/**
 * File:        MainView.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * MainView class
 */

class MainView extends CoreView
{
	public function renderPredefinedCss()
	{
		parent::renderPredefinedCss();
		// render superFish every time
		//$this->renderSuperFishCss();
	}
	
	public function renderPredefinedJs()
	{
		parent::renderPredefinedJs();
		// render superFish every time
		//$this->renderSuperFishJs();
	}
	
	
	/**
	 * Renders FormCheck form validation
	 *
	 */
	protected function renderFormCheck()
	{
		$this->addCss('/scripts/formcheck/theme/classic/formcheck.css');
		$this->addJs('/scripts/formcheck/core.js');
		$this->addJs('/scripts/formcheck/more.js');
		$this->addJs('/scripts/formcheck/lang/en.js');
		$this->addJs('/scripts/formcheck/formcheck.js');
		$script = "window.addEvent('domready', function(){new FormCheck('formular', {
				display : {
					errorsLocation : 3,
					indicateErrors : 2,
					flashTips : true,
					fadeDuration : 1000
				}
			})});
			";
		$this->addScript($script);

	}
	
	protected function renderJQueryForm()
	{
		$this->addCss('/scripts/jqueryform/css/jqtransform.css');
		$this->addCss('/scripts/jqueryform/css/style.css');
		$this->addJs('/scripts/jqueryformjs/jquery.jqtransform.js');
		$this->addJs('/scripts/jqueryformjs/jquery.validate.js');
		$this->addJs('/scripts/jqueryformjs/jquery.form.js');
		$this->addJs('/scripts/jqueryformjs/js/websitechange.js');
	}
	
	protected function renderSuperFishCss()
	{
		$this->addCss('/scripts/superfish/css/superfish.css');
	}
	
	protected function renderSuperFishJs()
	{
		$this->addJs('/scripts/superfish/js/superfish.js');
		$this->addJs('/scripts/superfish/js/hoverIntent.js');
	}

	protected function renderDataTables()
	{
		// Datatables
		$this->addCss('/scripts/dataTables/css/demos.css');
		$this->addJs('/scripts/dataTables/js/jquery.js');
		$this->addJs('/scripts/dataTables/js/jquery.dataTables.min.js');
		
		$script = '
		var oTable;
			var asInitVals = new Array();
			
			$(document).ready(function() {
				oTable = $(\'#listTable\').dataTable( {
					"oLanguage": {
						"sSearch": "Search all columns:"
					}
				} );
				
				$("tfoot input").keyup( function () {
					/* Filter on the column (the index) of this element */
					oTable.fnFilter( this.value, $("tfoot input").index(this) );
				} );
				
				
				
				/*
				 * Support functions to provide a little bit of user friendlyness to the textboxes in 
				 * the footer
				 */
				$("tfoot input").each( function (i) {
					asInitVals[i] = this.value;
				} );
				
				$("tfoot input").focus( function () {
					if ( this.className == "search_init" )
					{
						this.className = "";
						this.value = "";
					}
				} );
				
				$("tfoot input").blur( function (i) {
					if ( this.value == "" )
					{
						this.className = "search_init";
						this.value = asInitVals[$("tfoot input").index(this)];
					}
				} );
			} );
		';
		$this->addScript($script);
	}

	public function listAction()
	{
		//$this->renderDataTables();
	}
	
	public function editAction()
	{
		$this->renderFormCheck();
		//$this->renderJQueryForm();
	}
	
	/**
	 * Generates the main menu
	 *
	 */
	public function renderMainMenu()
	{
		$menuFile = $this->_modulePath . DS . MF_DESCRIPTORS_FOLDER . DS . 'menusDescriptor.xml';
		// If file exists, append the existing menus to the present one
		if (file_exists($menuFile))
		{
			$xml = simplexml_load_file($menuFile);
			foreach($xml->children() as $child)
			{
				$this->mainMenu[] = $child->getName();
			}
		}		
	}
}
