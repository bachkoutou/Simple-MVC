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
 * File:        ImageHelper.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * A class to generate resize and crop images. 
 */
class ImageHelper 
{

	// Detect witch version of GD is use
	private $gd2 = false;

	// The source of the image
	private $img_src = null;

	// Array of information on the image
	private $img_size = null;

	// Type of image (IMG_JPG, IMG_GIF, IMG_PNG)
	private $img_type = IMG_JPG;

	// This is the quality use for output image (only for JPG format)
	private $quality = 80;


	/**
     * Constructor
     *
     * @param string $filename	Source image file
     *
     */
	public function __construct($fileName)
	{
		if (!file_exists($fileName))
		{
            throw new Exception('File not found : ' . $fileName);
		}

		if($this->img_src != null) imagedestroy($this->img_src);

		$this->img_size = getimagesize($fileName);

		switch($this->img_size[2])
		{
			case 1 :
				if (imagetypes() & IMG_GIF)
					$this->img_src = imagecreatefromgif($fileName);
					$this->img_type = IMG_GIF;
					break;

			case 2 :
				if (imagetypes() & IMG_JPG)
					$this->img_src = imagecreatefromjpeg($fileName);
					$this->img_type = IMG_JPG;
					break;

			case 3 :
				if (imagetypes() & IMG_PNG)
					$this->img_src = imagecreatefrompng($fileName);
					$this->img_type = IMG_PNG;
					break;

		}

		if($this->img_src == null)
		{
			//throw new Exception(_NOT_SUPPORTED_TYPE);
			return;
		}

		// FRFR - Detecte si on utilise GD2 ou non (les fonctions sont différentes selon la version utilisée
		$this->gd2 = (function_exists("imagecreatetruecolor") && function_exists("imagecopyresampled"));
	}

	/**
     * Destructor
	 * 
     */
	public function __destruct()
	{
		if($this->img_src != null)
			imagedestroy($this->img_src);
	}

	/**
     * Set the quality of the output image file
     *
     * @param int	$quality 	ranges from 0 (worst quality, smaller file) to 100 (best quality, biggest file). The default is 80.
     *
     */
	public function setQuality($q = 80)
	{
		$this->quality = $q;
	}

	/**
	 *	Create an image thumbnail and keep the good proportions. 	
	 *
	 *	@param String	$destination	Name of the output file
	 *	@param Int		$width			Width of the ouptut image file
	 *	@param Int		$height			Height of the output image file
	 *	@param Int		$type			Image output format (Default : IMG_JPG)
	 */
	public function thumb($destination, $width, $height, $type = IMG_JPG)
	{
		$w = $this->img_size[0];
		$h = $this->img_size[1];

		$ratioFull = $w/$h;
		$ratioThumb = $width/$height;

		if($ratioThumb > $ratioFull)
		{
			$src_w = $w;
			$src_h = $src_w / $ratioThumb;
			$src_x = 0;
			$src_y = ($h - $src_h)/2;
		}
		else
		{
			$src_h = $h;
			$src_w = $src_h * $ratioThumb;
			$src_y = 0;
			$src_x = ($w - $src_w)/2;
		}

		if ($this->gd2)
		{
			$destsrc = imagecreatetruecolor($width,$height);
			$bgc = imagecolorallocate($destsrc, 255, 255, 255);
			imagefilledrectangle($destsrc, 0, 0, $width, $height, $bgc);
			imagecopyresampled($destsrc, $this->img_src, 0, 0, $src_x, $src_y, $width, $height, $src_w, $src_h);
		}
		else
		{
			$destsrc = imagecreate($width,$height);
			$bgc = imagecolorallocate($destsrc, 255, 255, 255);
			imagefilledrectangle($destsrc, 0, 0, $width, $height, $bgc);
			imagecopyresized($destsrc, $this->img_src, 0, 0, $src_x, $src_y, $width, $height, $src_w, $src_h);
		}

		self::saveToFile($destsrc,$destination, $type, $this->quality);
		imagedestroy($destsrc);
	}

	/**
	 *	Resize the image using a specific maximum size. 
	 *
	 *  @param String	$destination	Name of the output file.
	 *  @param Int		$width			Maximum width of the output file
	 *	@param Int		$height			Maximum height of the output file
	 *	@param Bool		$proportional	Define if we want to keep the proportions
     *  @param Int		$type        	Image output format (Default : IMG_JPG)
	 */
	public function resize($destination, $width, $height, $proportional = true, $type = IMG_JPG)
	{
		$w = $this->img_size[0];
		$h = $this->img_size[1];

		if(!$proportional)
		{
			$im_w = $width;
			$im_h = $height;
		}
		elseif ($h<$height && $w<$width)
		{
			$im_w = $w;
			$im_h = $h;
		}
		elseif ((($h * $width) / $w) > $height)
		{
			$im_w = ($w * $height) / $h;
			$im_h = $height;
		}
		else
		{
			$im_w = $width;
			$im_h = ($h * $width) / $w;
		}

		if ($this->gd2)
		{
			$destsrc = imagecreatetruecolor($im_w,$im_h);
			$bgc = imagecolorallocate($destsrc, 255, 255, 255);
			imagefilledrectangle($destsrc, 0, 0, $im_w, $im_h, $bgc);
			imagecopyresampled($destsrc,$this->img_src,0,0,0,0,$im_w,$im_h,$w,$h);
		}
		else
		{
			$destsrc = imagecreate($im_w,$im_h);
			$bgc = imagecolorallocate($destsrc, 255, 255, 255);
			imagefilledrectangle($destsrc, 0, 0, $im_w, $im_h, $bgc);
			imagecopyresized($destsrc,$this->img_src,0,0,0,0,$im_w,$im_h,$w,$h);
		}

		self::saveToFile($destsrc,$destination, $type, $this->quality);
		imagedestroy($destsrc);
	}

	/**
	 *	Create a crop image
	 *
     *  @param String	$destination	Name of the output file
     *  @param Int		$width			Width of the ouptut image file
     *  @param Int		$height			Height of the output image file
	 *	@param Int		$src_x1			x-coordinate of top left source point
	 *	@param Int		$src_y1			y-coordinate of top left source point
	 *	@param Int		$src_x2			x-coordinate of bottom right source point
	 *	@param Int		$src_y2			y-coordinate of bottom right source point
	 *  @param Int		$type			Image output format (Default : IMG_JPG)
	 */
	public function crop($destination, $width, $height, $src_x1, $src_y1, $src_x2, $src_y2, $type = IMG_JPG)
	{
        if ($this->gd2)
		{
			$destsrc = imagecreatetruecolor($width,$height);
			$bgc = imagecolorallocate($destsrc, 255, 255, 255);
			imagefilledrectangle($destsrc, 0, 0, $width, $height, $bgc);
			imagecopyresampled($destsrc, $this->img_src, 0, 0, $src_x1, $src_y1, $width, $height, abs($src_x1-$src_x2), abs($src_y1-$src_y2));
		}
		else
		{
			$destsrc = imagecreate($width,$height);
			$bgc = imagecolorallocate($destsrc, 255, 255, 255);
			imagefilledrectangle($destsrc, 0, 0, $width, $height, $bgc);
			imagecopyresized($destsrc, $this->img_src, 0, 0, $src_x1, $src_y1, $width, $height, abs($src_x1-$src_x2), abs($src_y1-$src_y2));
		}

		self::saveToFile($destsrc,$destination, $type, $this->quality);
		imagedestroy($destsrc);
	}


	/**
	 * Save the image an a given fomrat
	 *
	 * @param	Ressource	$src	Image ressource to create
	 * @param 	Int			$type	Format of the ouptut image file (IMG_GIF, IMG_JPG, IMG_PNG)
	 * @param	Int			$quality	Quality of the output image
	 */

	 private static function saveToFile($src, $dest, $type, $quality = 75)
	 {
		if (!(imagetypes() & $type))
			throw new Exception(_NOT_SUPPORTED_TYPE);


		// Check (and even create) directory
		$dn = dirname($dest);
		if (!is_dir($dn))
		{
			if (!@mkdir($dn, 0777, true))
			{
				trigger_error("can't create directory $dn", E_USER_ERROR);
				return false;
			}
		}

		switch($type)
		{
			case IMG_GIF:
				if (!@imagegif($src, $dest))
				{
					trigger_error("can't create file $dest", E_USER_ERROR);
					return false;
				}
				break;

			case IMG_JPG:
				if (!@imagejpeg($src, $dest, $quality))
				{
					trigger_error("can't create file $dest", E_USER_ERROR);
					return false;
				}
				break;

			case IMG_PNG:
				if (!@imagepng($src, $dest))
				{
					trigger_error("can't create file $dest", E_USER_ERROR);
					return false;
				}
				break;
		}
	 }

}

