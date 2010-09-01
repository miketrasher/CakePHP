<?php
class FilesystemComponent extends Object
{
	/**
	 * 
	 * Gibt das übergeordnete Verzeichnis zurück
	 *
	 * @param string, the path
	 * @return string, the parent folder
	 * @author Mike Trasher
	 */    
    function pd($path = null)
    {
        if (!$path)
		{
			return;
		}

		if (substr($path, - 1) == DS)
		{
			$path = substr($path, 0, strlen($path) - 1); #Entfernt den Slash am Ende des Pfades.
		}

		$wd = strrchr($path, DS); #Gibt den letzten Ordner im Pfad zurück

		$path = substr($path, 0, -strlen($wd)); #Gibt den Parent-Pfad zurück
		
        return $path;
    }
	
	
	/**
	 *
	 * Wandelt eine Zeichenkette in das angegebene Schema um.
	 *
	 * @param string, the sign
	 * @param string, the conversion mode
	 * @return string
	 * @author Mike Trasher
	 */
	function rename($string = null, $mode = null)
	{
		if ($mode == 'ftp')
		{
			$string = strtolower($string);
			
			$search = array('ö', 'ä', 'ü', 'Ö', 'Ä', 'Ü', 'ß', '&', '%');
			$replace = array('oe', 'ae', 'ue', 'oe', 'ae', 'ue', 'ss', 'und', '');
			
			$string = str_replace($search, $replace, $string);
			
			// weiter gehts...
			$search = array('/', ' ', '!', '#', '*', '"', '\'', 'é', 'è', '+', '$', '€');
			$replace = array('', '_', '', '', '', '', '', 'e', 'e', 'und', 'dollar', 'euro');
			
			$string = str_replace($search, $replace, $string);
			
			// und weiter gehts...
			$search = array(';', ':', '§', '?', '=');
			$replace = array('', '', '', '', '');
			
			return str_replace($search, $replace, $string);
		}
		else
		{
			return $string;
		}
	}
	
	/**
	 *
	 * Gibt die Endung einer Datei zurück.
	 *
	 * @param string, the filename
	 * @return string, the extension (without a dot)
	 * @author Mike Trasher
	 */
	function getExtension($file)
	{
		$pathinfo = pathinfo($file['name']);
		$ext = $pathinfo['extension'];
		return $ext;
	}
	
	/**
	 *
	 * Gibt die Endung einer Datei zurück.
	 *
	 * @param string, the tempfile-Array
	 * @param string, the destination path
	 * @param string, the new filename
	 * @return File, the reference to the new file
	 * @author Mike Trasher
	 */
	function createFileFromTempfile($file, $path, $name = null)
	{
		$fileTemp = new File($file['tmp_name']);
		$content = $fileTemp->read();
		$fileTemp->close();
		
		$fileNew = ($name) ? new File($path.DS.$name) : new File($path.DS.$file['name']);
		$fileNew->write($content);
		$fileNew->close();
		
		return $fileNew;
	}
	
	/**
	 *
	 * Lädt eine oder mehrere Dateien hoch.
	 *
	 * @param Array, the tempfile-Array
	 * @param Array, the options (path, name, Model)
	 * @return Boolean, the indicator of success
	 * @author Mike Trasher
	 */
	function uploadFiles($files, $options)
	{
		foreach ($files as $file_index => $tmp_file)
		{
			if ($this->isUploadedFile($tmp_file))
			{	
				$filename = $tmp_file['name'];
				
				if ($options[$file_index]['name'])
				{
					$filenameForSprint = $options[$file_index]['name'][0];
					$filenameExt = $this->getExtension($tmp_file);
					$filenameAdditional = $options[$file_index]['name'][1];
					$filename = sprintf($filenameForSprint, $filenameExt, $filenameAdditional);
				}
				
				$this->createFileFromTempfile($tmp_file, $options[$file_index]['path'], $filename);
				
				if ($options[$file_index]['model'])
				{
					$Model = &$options[$file_index]['model'];
					$Model->saveField($file_index, $filename);
				}
			}
		}
		
		return true;
	}
	
	/**
	 *
	 * Prüft, ob eine Datei in das temporäre Verzeichnis von PHP geladen wurde
	 *
	 * @param string, the tempfile-Array
	 * @return Boolean, the indicator of success
	 * @author Mike Trasher
	 */
	function isUploadedFile($file)
	{
		if ((isset($file['error']) && $file['error'] != 0) || (empty($file['tmp_name']))) 
		{
			return false;
		}
		
		return true;
	}
	
	/**
	 *
	 * Löscht eine Datei.
	 *
	 * @param string, the path including the file name
	 * @return Boolean, the indicator of success
	 * @author Mike Trasher
	 */
	function deleteFile($path)
	{
		$file = new File($path);
		if ($file->delete())
		{
			return true;
		}
		
		return false;
	}
}
?>