<?php

class MerkzettelModelUsermerkzettel extends JModelItem
{
	/**
	 * @var array messages
	 */
	//protected $messages;

	/**
	 * @var object item
	 */
	protected $item;

	protected function populateState()
	{

		// Get values and params from menu

		$jinput = JFactory::getApplication()->input;
		$id     = $jinput->get('Itemid', '', 'text');
		$this->setState('pimmel.id', $id);

		//Kint::dump($id);
		// Load the parameters.
		$this->setState('params', JFactory::getApplication()->getParams());
		parent::populateState();
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $type    The table name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A JTable object
	 *
	 * @since   1.6
	 */
	public function getTable($type = 'Usermerkzettel', $prefix = 'MerkzettelTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);

	}


	public function getAllUserDownloads()
	{

		// Get current user

		$user = JFactory::getUser();
		$userId = $user->id;

		// Get a TableHelloWorld instance
		$table = $this->getTable();

		// Load all downloads

		return $table->loadAllByUser($userId);
	}



	// Save values from ajax call

	public function storeValuesFromAjax($downloadId,$userId)
	{

		// Get a TableHelloWorld instance
		$table = $this->getTable();

		$downloadValues = [
			'user_id' => $userId,
			'download_id' => $downloadId
		];

		return $table->save($downloadValues);
	}

	// Delete values from ajax call

	public function deleteValuesFromAjax($downloadId)
	{

		$user = JFactory::getUser();
		$userId = $user->id;
		// Get a TableHelloWorld instance
		$table = $this->getTable();

		return $table->downloadDelete($downloadId, $userId);
	}

	// Create ZIP
	/* creates a compressed zip file */


	function getCreateZip($overwrite = false) {
		$userDownloads = $this->getAllUserDownloads();

		$files = array();

		foreach($userDownloads as $download){
			$files[] = JPATH_ROOT.'/user_downloads/'.$download->download_url;
		}


		$destination = $this->getFileLocation();
		//if the zip file already exists and overwrite is false, return false
		if(file_exists($destination) && !$overwrite) { return false; }
		//vars
		$valid_files = array();
		//if files were passed in...
		if(is_array($files)) {
			//cycle through each file
			foreach($files as $file) {
				//make sure the file exists
				if(file_exists($file)) {
					$valid_files[] = $file;
				}
			}
		}
		//if we have good files...
		if(count($valid_files)) {
			//create the archive
			$zip = new ZipArchive();
			if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
				return false;
			}
			//add the files
			foreach($valid_files as $file) {
				$zip->addFile($file,substr($file,strrpos($file,'/') + 1));
			}
			//debug
			//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;

			//close the zip -- done!
			$zip->close();

			// http headers for zip downloads
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"".$destination."\"");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".filesize($destination));
			ob_end_flush();
			@readfile($destination);
			unlink($destination);
			//return $destination;
		}
		else
		{

			return false;
		}
	}

	/*
	function getCreateZip(){

		$downloadFilePath = JPATH_ROOT.'/user_downloads/usercache/';
		$userDownloads = $this->getAllUserDownloads();

		$files = array();

		foreach($userDownloads as $download){
			$files[] = JPATH_ROOT.'/user_downloads/'.$download->download_url;
		}


		$destination = $this->getFileLocation();
		var_dump($downloadFilePath);
		var_dump($destination);
		var_dump($files);

		$zip = new ZipArchive();
		//create the file and throw the error if unsuccessful
		if ($zip->open($destination, ZIPARCHIVE::CREATE )!==TRUE) {
			exit("cannot open <$destination>\n");
		}
		//add each files of $file_name array to archive
		foreach($files as $files)
		{
			var_dump($files);
			$zip->addFile($downloadFilePath.$files,$files);
			echo $file_path.$files,$files;

		}
		$zip->close();
		//then send the headers to force download the zip file
		/*header("Content-type: application/zip");
		header("Content-Disposition: attachment; filename=$destination");
		header("Pragma: no-cache");
		header("Expires: 0");
		readfile("$destination");
		return $destination;
	}
*/
	// Set custom file location for zip

	public function getFileLocation(){
		$hash = $this->randHash();
		$path = 'Merkzettel-Benutzer-'.$hash.'-'.date('d-m-y-h-i-s').'.zip';
		return $path;
	}

	// Get base path to clean download

	public function getBasePath(){
		return JPATH_ROOT;
	}

	function randHash($len=32)
	{
		return substr(md5(openssl_random_pseudo_bytes(20)),-$len);
	}

}