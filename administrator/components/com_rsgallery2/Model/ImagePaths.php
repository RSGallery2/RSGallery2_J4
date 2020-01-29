<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 * @copyright   (C) 2016-2018 RSGallery2 Team
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author          finnern
 * RSGallery is Free Software
 */

namespace Joomla\Component\Rsgallery2\Administrator\Model;

use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die;

//use Joomla\CMS\Factory;


/**
 * Merge several parts of URL or filesystem path in one path
 * Examples:
 *  echo merge_paths('stackoverflow.com', 'questions');           // 'stackoverflow.com/questions' (slash added between parts)
 *  echo merge_paths('usr/bin/', '/perl/');                       // 'usr/bin/perl/' (double slashes are removed)
 *  echo merge_paths('en.wikipedia.org/', '/wiki', ' Sega_32X');  // 'en.wikipedia.org/wiki/Sega_32X' (accidental space fixed)
 *  echo merge_paths('etc/apache/', '', '/php');                  // 'etc/apache/php' (empty path element is removed)
 *  echo merge_paths('/', '/webapp/api');                         // '/webapp/api' slash is preserved at the beginnnig
 *  echo merge_paths('http://google.com', '/', '/');              // 'http://google.com/' slash is preserved at the end
 *
 * @param string $path1
 * @param string $path2
 */
function path_join($path1, $path2)
{
	$paths    = func_get_args();
	$last_key = func_num_args() - 1;
	array_walk($paths, function (&$val, $key) use ($last_key) {
		switch ($key)
		{
			case 0:
				$val = rtrim($val, '/ ');
				break;
			case $last_key:
				$val = ltrim($val, '/ ');
				break;
			default:
				$val = trim($val, '/ ');
				break;
		}
	});

	$first = array_shift($paths);
	$last  = array_pop($paths);
	$paths = array_filter($paths); // clean empty elements to prevent double slashes
	array_unshift($paths, $first);
	$paths[] = $last;

	return implode('/', $paths);
}


class ImagePaths {
	public $galleryRoot;
	public $imageSizes;

	public $originalBasePath;
	public $thumbBasePath;
	public $sizeBasePaths; // 800x6000, ..., ? display:J3x
	//	toDo: watermark ...

	protected $galleryRootUrl;
	protected $originalUrl;
	protected $thumbUrl;
	protected $sizeUrls;
	//	toDo: watermark ...

	public function __construct($rootPath = "", $galleryId = 0, $imageSizes = []) {
		$this->galleryRoot = path_join(JPATH_ROOT, $rootPath, $galleryId);

		$this->originalBasePath = path_join($this->galleryRoot, 'original');
		$this->thumbBasePath    = path_join($this->galleryRoot, 'thumbs');

		$this->imageSizes = $imageSizes;
		foreach ($imageSizes as $imageSize) {
			$this->sizeBasePaths[$imageSize] = path_join($this->galleryRoot, $imageSize);
		}

		$this->galleryRootUrl = path_join(Uri::root(), $rootPath, $galleryId);

		$this->originalUrl = path_join($this->galleryRootUrl, 'original');
		$this->thumbUrl    = path_join($this->galleryRootUrl, 'thumbs');

		// $this->imageSizes = $imageSizes;
		foreach ($imageSizes as $imageSize) {
			$this->sizeUrls[$imageSize] = path_join($this->galleryRootUrl, $imageSize);
		}

	}

	public function getOriginal ($fileName=''){
		return path_join ($this->originalBasePath, $fileName);
	}
	public function getThumbPath ($fileName=''){
		return path_join ($this->thumbBasePath, $fileName);
	}
	public function getSizePath ($imageSize, $fileName=''){
		return path_join ($this->sizeBasePaths [$imageSize], $fileName);
	}

	public function getOriginalUrl ($fileName=''){
		return path_join ($this->OriginalUrl, $fileName);
	}
	public function getThumbUrl ($fileName=''){
		return path_join ($this->thumbUrl, $fileName);
	}
	public function getSizeUrl ($imageSize, $fileName=''){
		return path_join ($this->sizeUrls [$imageSize], $fileName);
	}

	public function createAllPaths($isCreateOriginal=true) {
		$isDone = Folder::create($this->galleryRoot);
		if($isDone) {

			$isDone = $isDone & Folder::create($this->originalBasePath);
			$isDone = $isDone & Folder::create($this->thumbBasePath);

			foreach ($this->sizeBasePaths as $sizePath)
			{
				$isDone = $isDone & Folder::create($sizePath);
			}
		}
		
		return $isDone;
	}

}


