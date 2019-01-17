<?php
	/* 	Author: Angel Magaña -- cheleguanaco@cheleguanaco.com
	*  	File: ./custom/include/CustomSplitAttachFolder.php
	*	
	*  	UploadStream extension to add use of date named  
	*  	subfolders to ./upload directory. 
	*	
	*	e.g. ./upload/2015/04/02/<MyApril2nd2015File.docRepresentedAsGUID>	
	*  	
	*	Enabled via: $sugar_config['upload_wrapper_class'] = 'CustomSplitAttachFolder';
	*/

class CustomSplitAttachFolder extends UploadStream
{
    public static $bean_date;

    public function __construct()
    {
    	parent::__construct();
    }
    
    /**
     * Get upload directory
     * @return string
     */
    public static function getDir()
    {
    	if(empty(parent::$upload_dir)) { 
            parent::$upload_dir = rtrim($GLOBALS['sugar_config']['upload_dir'], '/\\') . self::getSubDir();
            if(empty(parent::$upload_dir)) {
                parent::$upload_dir = 'upload' . self::getSubDir();
            }
            
            if(!file_exists(parent::$upload_dir)) { 
                sugar_mkdir(parent::$upload_dir, 0755, true);
            }
        }
        
        return parent::$upload_dir; 
    }
    
    public static function getSubDir()
    {
		$beandt = new DateTime(self::$bean_date);
    	
    	$subdir = $beandt->format('/Y/m/d');
    	
    	return $subdir;
    }
    
    /**
     * Get real FS path of the upload stream file
     * Non-static version for overrides
     * @param string $path Upload stream path (with upload://)
     * @return string FS path
     */
    public function getFSPath($path)
    {	
    	$path = substr($path, strlen(self::STREAM_NAME)+3); // cut off upload://
    	$path = str_replace("\\", "/", $path); // canonicalize path
    	if($path == ".." || substr($path, 0, 3) == "../" || substr($path, -3, 3) == "/.." || strstr($path, "/../")) {
    		$GLOBALS['log']->fatal("Invalid uploaded file name supplied: $path");
    		return null;
    	}
    	
        return self::getDir()."/".$path;
    }
    
    //Here we open stream to download an attachment
    public function stream_open($path, $mode)
    {   
        $fullpath = $this->getFSPath($path, 1);
        if(empty($fullpath)) return false;
        if($mode == 'r') {
            $this->fp = fopen($fullpath, $mode);
        } else {
            // if we will be writing, try to transparently create the directory
            $this->fp = @fopen($fullpath, $mode);
            if(!$this->fp && !file_exists(dirname($fullpath))) {
                mkdir(dirname($fullpath), 0755, true);
                $this->fp = fopen($fullpath, $mode);
            }
        }
        return !empty($this->fp);
    }
}
