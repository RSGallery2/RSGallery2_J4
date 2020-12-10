<?php
/**
 * This class handles version management for RSGallery2
 *
 * @version       $Id: version.rsgallery2.php 1099 2012-10-08 11:37:43Z mirjam $
 * @package       RSGallery2
 * @copyright (C) 2003-2019 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery is Free Software
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Helper;

use Joomla\CMS\Factory;

// no direct access
\defined('_JEXEC') or die;

/**
 * Version information class. Lives from the manifest file which it loads
 * (formely based on the Joomla version class)
 *
 * @package RSGallery2
 */
class rsgallery2Version
{
    // ToDO: Create singleton

    //Note: also set version number in config.class.php function rsgConfig
//    var $PRODUCT = 'RSGallery2';
   protected $name = 'RSGallery2';
    // Main Release Level: x.y.z
//    var $RELEASE = '5.0.999';
	protected $version = '5.0.999';
//    var $RELDATE = '28 Feb. 2016';
	protected $creationDate = '04 Oct. 2019';
//    var $COPYRIGHT = '&copy; 2005 - 2019';
	protected $copyright = '04 Oct. 2019';

//    var $URL = '<strong><a class="rsg2-footer" href="http://www.rsgallery2.org">RSGallery2</a></strong>';

	/**
	
	 * @since __BUMP_VERSION__
    */
    function __construct()
    {
        //--- collect data from manifest -----------------
        $db = Factory::getDbo();
        $query = $db->getQuery(true)
			->select($db->quoteName('manifest_cache'))
            ->from($db->quoteName('#__extensions'))
            ->where($db->quoteName('element') . ' = ' . $db->quote('com_rsgallery2'));
        $db->setQuery($query);

        // manifest Array (
        //	[name] => com_rsg2
        //	[type] => component
        //	[creationDate] => July 2014
        //	[author] => RSGallery2 Team
        //	[copyright] => (c) 2014 RSGallery2 Team
        //	[authorEmail] => team2@rsgallery2.org
        //	[authorUrl] => http://www.rsgallery2.org
        //	[version] => 1.0.2
        //	[description] => COM_RSGALLERY2_XML_DESCRIPTION
        //	[group] =>
        //	[filename] => rsg2
        //)

        $manifest = json_decode($db->loadResult(), true);

        //--- assign data from manifest -----------------

        //	[name] => com_rsg2
	    //	* $this->PRODUCT = $manifest['name'];
	    $this->name = $manifest['name'];
        //	[creationDate] => July 2014
	    //	* $this->RELDATE = $manifest['creationDate'];
	    $this->creationDate = $manifest['creationDate'];
        //	[author] => RSGallery2 Team
	    //	* $this->AUTHOR = $manifest['author'];
        //	[copyright] => (c) 2014 RSGallery2 Team
	    //	* $this->COPYRIGHT = $manifest['copyright'];
	    $this->copyright = $manifest['copyright'];
        //	[authorEmail] => team@rsgallery2.org
	    //	* $this->EMAIL = $manifest['authorEmail'];
        //	[authorUrl] => http://www.rsgallery2.org
        // Old: = '<strong><a class="rsg2-footer" href="http://www.rsgallery2.org">RSGallery2</a></strong>';
	    //	* $this->URL = $manifest['authorUrl'];
        //	[version] => 1.0.2
	    //	* $this->RELEASE = $manifest['version'];
	    $this->version = $manifest['version'];
	     //    [description] => COM_RSGALLERY2_XML_DESCRIPTION
	     //	* $this->DESCRIPTION = $manifest['description'];
	    /**/
    }

    /**
     * @return string Long format version
     * @since __BUMP_VERSION__
     */
    function getLongVersion()
    {
        return $this->name . ' '
            . ' [' . $this->version . '] '
            . '(' . $this->creationDate . ')' . ' '
	        . $this->copyright
            ;
    }

    /**
     * @return string Short version format
     * @since __BUMP_VERSION__
     */
    function getShortVersion()
    {
	    return $this->name . ' '
		    . ' [' . $this->version . '] '
		    . '(' . $this->creationDate . ')' . ' '
		    ;
    }

    /**
     * @return string with footer added
     * @since __BUMP_VERSION__
     * /
    //function getCopyrightVersion()
    function getFooterCopyrightVersion()
    {
        return $this->name . ' ' . $this->version . '<br />'
            . $this->COPYRIGHT . ' <strong><a class="rsg2-footer" href="http://www.rsgallery2.org">RSGallery2</a></strong>. All rights reserved.';
    }
	/**/
    /**
     * Plain version
     * @return string PHP standardized version format
     * @since __BUMP_VERSION__
     */
    function getVersion()
    {
        return $this->version;
    }

    /**
     * checks if checked version is lower, equal or higher that the current version
     *
     * @param $version
     *
     * @return int -1 (lower), 0 (equal) or 1 (higher)
     * @since __BUMP_VERSION__
     */
    function checkVersion($version)
    {
        $check = version_compare($version, $this->version);

        return $check;
    }

}


