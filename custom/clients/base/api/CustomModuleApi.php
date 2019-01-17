<?php
	/* 	Author: Angel Magaña -- cheleguanaco@cheleguanaco.com
	*  	File: ./custom/clients/base/api/CustomModuleApi.php
	*	
	*  	Custom Endpoint to allow for custom directory structure  
	*  	in ./upload directory. 
	*	
	*	e.g. ./upload/2015/04/02/<MyApril2nd2015File.docRepresentedAsGUID>	
	*  	
	*	Enabled via: $sugar_config['upload_wrapper_class'] = 'CustomSplitAttachFolder';
	*/

require_once('clients/base/api/ModuleApi.php');

class CustomModuleApi extends ModuleApi
{
	/* Custom method for setting date parameters needed to create dir structure */
	protected function moveTemporaryFiles(array $args, SugarBean $bean)
	{
		CustomSplitAttachFolder::$bean_date = $bean->date_entered;
	
		parent::moveTemporaryFiles($args, $bean);
	}

}

?>
