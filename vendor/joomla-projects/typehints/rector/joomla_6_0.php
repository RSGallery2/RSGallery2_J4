<?php
/**
 * @package   JTypeHints
 * @copyright Copyright (c) 2017-2023 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

/**
 * Rector 2 configuration for converting legacy Joomla! classes to namespaced ones, compatible with Joomla! 6.0
 */
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(
        RenameClassRector::class,
        [
            'Joomla\CMS\Input\Cookie'                        => 'Joomla\Input\Cookie',
            'Joomla\CMS\Input\Files'                         => 'Joomla\Input\Files',
            'Joomla\CMS\Input\Input'                         => 'Joomla\Input\Input',
            'Joomla\CMS\Input\Json'                          => 'Joomla\Input\Json',
            'Joomla\CMS\Filesystem\File'                     => 'Joomla\Filesystem\File',
            'Joomla\CMS\Filesystem\FilesystemHelper'         => 'Joomla\Filesystem\Helper',
            'Joomla\CMS\Filesystem\Folder'                   => 'Joomla\Filesystem\Folder',
            'Joomla\CMS\Filesystem\Patcher'                  => 'Joomla\Filesystem\Patcher',
            'Joomla\CMS\Filesystem\Path'                     => 'Joomla\Filesystem\Path',
            'Joomla\CMS\Filesystem\Stream'                   => 'Joomla\Filesystem\Stream',
            'Joomla\CMS\Filesystem\Support\StringController' => 'Joomla\Filesystem\Support\StringController',
            'Joomla\CMS\Filesystem\Stream\StreamString'      => 'Joomla\Filesystem\Stream\StringWrapper',
        ]
    );
};
