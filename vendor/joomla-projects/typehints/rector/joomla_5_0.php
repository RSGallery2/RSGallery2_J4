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
 * Rector 2 configuration for converting legacy Joomla! classes to namespaced ones, compatible with Joomla! 5.0
 */
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(
        RenameClassRector::class,
        [
            'ContactHelperRoute'   => 'Joomla\Component\Contact\Site\Helper\RouteHelper\ContactHelperRoute',
            'JHtmlIcon'            => 'Joomla\Component\Content\Administrator\Service\HTML\Icon',
            'FinderHelperRoute'    => 'Joomla\Component\Finder\Site\Helper\RouteHelper\FinderHelperRoute',
            'NewsfeedsHelperRoute' => 'Joomla\Component\Newsfeeds\Site\Helper\RouteHelper\NewsfeedsHelperRoute',
            'TagsHelperRoute'      => 'Joomla\Component\Tags\Site\Helper\RouteHelper\TagsHelperRoute',
            'BannersHelper'        => 'Joomla\Component\Banners\Administrator\Helper\BannersHelper',
            'CategoriesHelper'     => 'Joomla\Component\Categories\Administrator\Helper\CategoriesHelper',
            'ContactHelper'        => 'Joomla\Component\Contact\Administrator\Helper\ContactHelper',
            'ContentHelper'        => 'Joomla\Component\Content\Administrator\Helper\ContentHelper',
            'ContenthistoryHelper' => 'Joomla\Component\Contenthistory\Administrator\Helper\ContenthistoryHelper',
            'FieldsHelper'         => 'Joomla\Component\Fields\Administrator\Helper\FieldsHelper',
            'FinderHelperLanguage' => 'Joomla\Component\Finder\Administrator\Helper\LanguageHelper',
            'InstallerHelper'      => 'Joomla\Component\Installer\Administrator\Helper\InstallerHelper',
            'MenusHelper'          => 'Joomla\Component\Menus\Administrator\Helper\MenusHelper',
            'ModulesHelper'        => 'Joomla\Component\Modules\Administrator\Helper\ModulesHelper',
            'NewsfeedsHelper'      => 'Joomla\Component\Newsfeeds\Administrator\Helper\NewsfeedsHelper',
            'PluginsHelper'        => 'Joomla\Component\Plugins\Administrator\Helper\PluginsHelper',
            'RedirectHelper'       => 'Joomla\Component\Redirect\Administrator\Helper\RedirectHelper',
            'TemplateHelper'       => 'Joomla\Component\Templates\Administrator\Helper\TemplateHelper',
            'TemplatesHelper'      => 'Joomla\Component\Templates\Administrator\Helper\TemplatesHelper',
            'UsersHelperDebug'     => 'Joomla\Component\Users\Administrator\Helper\DebugHelper',
            'UsersHelper'          => 'Joomla\Component\Users\Administrator\Helper\UsersHelper',
        ]
    );
};
