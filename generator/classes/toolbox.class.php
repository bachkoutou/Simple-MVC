<?php
class Toolbox
{
	/**
  *  Function which check if the given path is absolute or not
  *
  *  @param String   $path   Path to check
  *
  *  @return Boolean Return TRUE if the path is absolute and FALSE if it's no
  */
	public static function isAbsolutePath($path)
	{
		$pattern = '@^([a-zA-Z]:|(\\\){2,2}|\/)@';
		return preg_match($pattern, $path);
	}
	/**
  * Save a string into a file (will create file and directory if needed)
  *
  * @param string pn         Full path name
  * @param string s          File content
  * @param bool   append     True to append content to file (default false)
  * @param bool   utf8       True to convert and write file in UTF-8 (default is false)
  *
  * @return true on success
  *
  **/
	public static function saveToFile($pn, $s, $append = false, $utf8 = true)
	{
		// Normalize path name
		$pn = str_replace("\\", "/", $pn);
		$pos = strrpos($pn, "/");
		$mask = umask(0);
		if ($pos)
		{
			// Retrieve directory name
			$dn = substr($pn, 0, $pos);

			// Check (and even create) directory
			if (!is_dir($dn))
			{
				if (!self::makeDirectory($dn))
				{
					umask($mask);
					trigger_error("can't create directory $dn (saveToFile $pn)", E_USER_ERROR);
					return false;
				}
			}
		}

		// write in replace or append mode
		$flags = 0;
		if ($append) $flags |= FILE_APPEND;

		if(file_exists($pn) && !is_writable($pn))
		chmod($pn, 0777);

		if (file_put_contents($pn, $s, $flags) === FALSE)
		{
			umask($mask);
			return false;
		}

		// change file mode
		@chmod($pn, 0777);
		umask($mask);

		return true;
	}

	/**
  * Create a directory with a specific mask
  *
  * @param string $path Path of directory to create
  * @param int $mask Optional mask (default is 0777)
  *
  * @return boolean TRUE on success, FALSE on failure
  */
	public static function makeDirectory($path, $mask = 0777)
	{
		if (is_dir($path))
		return true;

		return @mkdir($path, $mask, true);
	}

	public static function getFileContent($filename)
	{
		return file_get_contents($filename);
	}
}
