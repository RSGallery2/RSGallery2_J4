<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2019-2026 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Rsgallery2;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\Database\DatabaseAwareInterface;
use Joomla\Database\DatabaseAwareTrait;

/**
 * Installation class to perform additional changes during install/uninstall/update
 *
     * @since      5.1.0
 */
class Pkg_Rsgallery2Script implements DatabaseAwareInterface
{
    use DatabaseAwareTrait;

    // for parent checks
    protected $minimumPhp      = JOOMLA_MINIMUM_PHP; // '8.1.0'
    protected $minimumJoomla   = '5.0.0';
    protected $allowDowngrades = false;
    // protected $allowDowngrades = true;




    protected $newRelease;
    protected $oldRelease;
    protected $oldManifestData;


    /**
     * Extension script constructor.
     *
     * @since 5.1.0  */
    public function __construct()
    {
        // Check if the default log directory can be written to, add a logger for errors to use it
        if (is_writable(JPATH_ADMINISTRATOR . '/logs')) {
            // Get the date for log file name
            $date = Factory::getDate()->format('Y-m-d');

            $logOptions['format']    = '{DATE}\t{TIME}\t{LEVEL}\t{CODE}\t{MESSAGE}';
            $logOptions['text_file'] = 'rsg2_pkd_install.' . $date . '.php';
            $logType                 = Log::ALL;
            $logChannels             = ['rsg2']; //jerror ...
            Log::addLogger($logOptions, $logType, $logChannels);

            try {
                Log::add(Text::_('\n>>RSG2 Installer construct'), Log::INFO, 'rsg2');
            } catch (RuntimeException $e) {
                // Informational log only
            }
        }

        // when component files are copied
        // $this->rsg2_basePath = JPATH_SITE . '/administrator/components/com_rsgallery2';
    }

    public function preflight($type, $parent): bool {

        // check min joomla, min php version and downgrade
        if (!parent::preflight($type, $parent)) {
            return false;
        }

        if ($type !== 'uninstall') {




        } // ! uninstall

        Log::add(Text::_('exit preflight') . $this->newRelease, Log::INFO, 'rsg2');

        return true;
    }



}
