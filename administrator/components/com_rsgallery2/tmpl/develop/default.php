<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @copyright  (C) 2003-2024 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 * RSGallery is Free Software
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

// HTMLHelper::_('bootstrap.framework');

//HTMLHelper::_('stylesheet', 'com_rsgallery2/backend/imagesProperties.css', array('version' => 'auto', 'relative' => true));
//HTMLHelper::_('script', 'com_rsgallery2/backend/imagesProperties.js', ['version' => 'auto', 'relative' => true]);
$this->document->getWebAssetManager()->usePreset('com_rsgallery2.backend.imagesProperties');

?>

<form action="<?php
echo Route::_('index.php?option=com_rsgallery2&view=develop'); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="d-flex flex-row">
        <?php
        if (!empty($this->sidebar)) : ?>
            <div id="j-sidebar-container" class="">
                <?php
                echo $this->sidebar; ?>
            </div>
            <!--div class="<?php
            echo (!empty($this->sidebar)) ? 'col-md-10' : 'col-md-12'; ?>"-->
            <div class="flex-fill">
                <div id="j-sidebar-container" class="">
                    <div id="j-toggle-sidebar-wrapper">
                        <div id="sidebar" class="sidebar">

                            <button class="btn btn-sm btn-secondary my-2 options-menu" type="button"
                                    data-bs-toggle="collapse" data-bs-target=".sub-sidebar-item"
                                    aria-controls="sidebar-nav" aria-expanded="false" aria-label="Toggle Menu">
                                <span class="fas fa-toggle-on" aria-hidden="true"></span>
                                <!--span class="sidebar-item-title">Toggle Menu</span-->
                            </button>

                            <nav class="main-nav-container sidebar-nav" aria-label="Main Menu" tabindex="-1"
                                 id="ui-skip-50">
                                <ul id="sub-menu12" class="nav flex-column main-nav ">
                                    <li class="item item-level-1">
                                        <a class="no-dropdown"
                                           href="index.php?option=com_rsgallery2&amp;view=rsgallery2"
                                           aria-label="Home Dashboard">
                                            <span class="icon-home-2" aria-hidden="true"/>
                                            <span class="sidebar-item-title sub-sidebar-item">Control panel</span>
                                        </a>
                                    </li>
                                    <li class="item item-level-1">
                                        <a class="no-dropdown" href="index.php?option=com_rsgallery2&amp;view=galleries"
                                           aria-label="Help">
                                            <span class="icon-images" aria-hidden="true"/>
                                            <span class="sidebar-item-title sub-sidebar-item">Galleries</span>
                                        </a>
                                    </li>
                                    <li class="item item-level-1">
                                        <a class="no-dropdown" href="index.php?option=com_rsgallery2&amp;view=upload"
                                           aria-label="Help">
                                            <span class="icon-upload" aria-hidden="true"/>
                                            <span class="sidebar-item-title sub-sidebar-item">Upload</span>
                                        </a>
                                    </li>
                                    <li class="item item-level-1">
                                        <a class="no-dropdown" href="index.php?option=com_rsgallery2&amp;view=images"
                                           aria-label="Help">
                                            <span class="icon-image" aria-hidden="true"/>
                                            <span class="sidebar-item-title sub-sidebar-item">Images</span>
                                        </a>
                                    </li>
                                    <li class="item item-level-1 active">
                                        <a class="no-dropdown"
                                           href="index.php?option=com_rsgallery2&amp;view=maintenance"
                                           aria-label="Help">
                                <span class="fas fa-wrench fa-fw" aria-hidden="true">
                                    <span class="sidebar-item-title sub-sidebar-item">Maintenance</span>
                                </span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>

                        </div>
                        <div id="j-toggle-sidebar"></div>
                    </div>
                </div>
            </div>

        <?php
        endif; ?>
        <div class="<?php
        if (!empty($this->sidebar)) {
            echo 'col-md-10';
        } else {
            echo 'col-md-12';
        } ?>">
            <div id="j-main-container" class="j-main-container">

                <?php
                echo '<h3> Developer default</h3>' . '<br>';
                echo 'default.php: ' . realpath(dirname(__FILE__)) . '<br>';
                ?>


            </div>
        </div>
    </div>

    <hr>
    <hr>


    <hr>
    <hr>


    <div class="row">
        <div id="j-sidebar-container" class="">
            <div id="j-toggle-sidebar-wrapper">
                <div id="sidebar" class="sidebar">

                    <button class="btn btn-sm btn-secondary my-2 options-menu" type="button" data-bs-toggle="collapse"
                            data-bs-target=".sidebar-nav"
                            aria-controls="sidebar-nav" aria-expanded="false" aria-label="Toggle Menu">
                        <span class="fas fa-align-justify" aria-hidden="true"></span>
                        <span class="sidebar-item-title">Toggle Menu</span>
                    </button>

                    <button class="btn btn-sm btn-secondary my-2 options-menu d-md-none" type="button"
                            data-bs-toggle="collapse" data-bs-target=".sidebar-nav"
                            aria-controls="sidebar-nav" aria-expanded="false" aria-label="Toggle Menu">
                        <span class="fas fa-align-justify" aria-hidden="true"></span>
                        Toggle Menu
                    </button>
                    <div class="sidebar-nav bg-light p-2 my-2">
                        <ul class="nav flex-column">
                            <li class="active">
                                <a href="index.php?option=com_rsgallery2&amp;view=rsgallery2"><span
                                            class="icon-home-2">  </span>Control panel</a>
                            </li>
                            <li class="active">
                                <a href="index.php?option=com_rsgallery2&amp;view=galleries"><span
                                            class="icon-images">  </span>Galleries</a>
                            </li>
                            <li class="active">
                                <a href="index.php?option=com_rsgallery2&amp;view=upload"><span
                                            class="icon-upload">  </span>Upload</a>
                            </li>
                            <li class="active">
                                <a href="index.php?option=com_rsgallery2&amp;view=images"><span
                                            class="icon-image">  </span>Images</a>
                            </li>
                            <li>
                                <a href="index.php?option=com_rsgallery2&amp;view=maintenance"><span
                                            class="icon-screwdriver">  </span>Maintenance</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div id="j-toggle-sidebar"></div>
            </div>
        </div>
    </div>


    <!--button class="navbar-toggler toggler-burger collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-sidebar-wrapper"
    <button class="navbar-toggler toggler-burger collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sub-sidebar-wrapper"
            aria-controls="sub-sidebar-wrapper" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div id="sub-sidebar-wrapper" class="sub-sidebar-wrapper sidebar-menu">
        <div id="sidebarmenu">
            <div class="sidebar-toggle item item-level-1">
                <a id="menu-collapse" href="#" aria-label="Toggle Menu">
                    <span id="menu-collapse-icon" class="fas fa-fw fa-toggle-on" aria-hidden="true"></span>
                    <span class="sidebar-item-title">Toggle Menu</span>
                </a>
            </div>
            <nav class="main-nav-container" aria-label="Main Menu" tabindex="-1" id="ui-skip-84"><ul id="menu12" class="nav flex-column main-nav metismenu">
                    <li class="item item-level-1"><a class="no-dropdown" href="index.php" aria-label="Home Dashboard"><span class="fas fa-home fa-fw" aria-hidden="true"></span><span class="sidebar-item-title">Home Dashboard</span></a></li>
                    <li class="item parent item-level-1"><a class="has-arrow" href="#" aria-label="Content" aria-expanded="false"><span class="fas fa-file-alt fa-fw" aria-hidden="true"></span><span class="sidebar-item-title">Content</span></a><span class="menu-dashboard"><a href="/joomla4x/administrator/index.php?option=com_cpanel&amp;view=cpanel&amp;dashboard=content"><span class="fas fa-th-large" title="Content Dashboard" aria-hidden="true"></span><span class="sr-only">Content Dashboard</span></a></span><ul id="collapse1" class="collapse-level-1 mm-collapse">
                            <li class="item item-level-2"><a class="no-dropdown" href="index.php?option=com_content&amp;view=articles" aria-label="Articles"><span class="sidebar-item-title">Articles</span></a><span class="menu-quicktask"><a href="index.php?option=com_content&amp;task=article.add"><span class="fas fa-plus" title="Add Article" aria-hidden="true"></span><span class="sr-only">Add Article</span></a></span></li>
                            <li class="item item-level-2"><a class="no-dropdown" href="index.php?option=com_categories&amp;view=categories&amp;extension=com_content" aria-label="Categories"><span class="sidebar-item-title">Categories</span></a><span class="menu-quicktask"><a href="index.php?option=com_categories&amp;extension=com_content&amp;task=category.add"><span class="fas fa-plus" title="Add Category" aria-hidden="true"></span><span class="sr-only">Add Category</span></a></span></li>
                            <li class="item item-level-2"><a class="no-dropdown" href="index.php?option=com_content&amp;view=featured" aria-label="Featured Articles"><span class="sidebar-item-title">Featured Articles</span></a></li>
                            <li class="item item-level-2"><a class="no-dropdown" href="index.php?option=com_workflow&amp;view=workflows&amp;extension=com_content" aria-label="Workflows"><span class="sidebar-item-title">Workflows</span></a></li>
                            <li class="divider item-level-2" role="presentation"><span></span></li>
                            <li class="item item-level-2"><a class="no-dropdown" href="index.php?option=com_fields&amp;view=fields&amp;context=com_content.article" aria-label="Fields"><span class="sidebar-item-title">Fields</span></a></li>
                            <li class="item item-level-2"><a class="no-dropdown" href="index.php?option=com_fields&amp;view=groups&amp;context=com_content.article" aria-label="Field Groups"><span class="sidebar-item-title">Field Groups</span></a></li>
                            <li class="divider item-level-2" role="presentation"><span></span></li>
                            <li class="item item-level-2"><a class="no-dropdown" href="index.php?option=com_media" aria-label="Media"><span class="sidebar-item-title">Media</span></a></li>
                            <li class="item item-level-2"><a class="no-dropdown" href="index.php?option=com_modules&amp;view=modules&amp;client_id=0" aria-label="Site Modules"><span class="sidebar-item-title">Site Modules</span></a><span class="menu-quicktask"><a href="index.php?option=com_modules&amp;view=select&amp;client_id=0"><span class="fas fa-plus" title="Add Site Module" aria-hidden="true"></span><span class="sr-only">Add Site Module</span></a></span></li>
                            <li class="item item-level-2"><a class="no-dropdown" href="index.php?option=com_modules&amp;view=modules&amp;client_id=1" aria-label="Administrator Modules"><span class="sidebar-item-title">Administrator Modules</span></a><span class="menu-quicktask"><a href="index.php?option=com_modules&amp;view=select&amp;client_id=1"><span class="fas fa-plus" title="Add Administrator Module" aria-hidden="true"></span><span class="sr-only">Add Administrator Module</span></a></span></li>
                        </ul>
                    </li>
                    <li class="item parent item-level-1"><a class="has-arrow" href="#" aria-label="Menus" aria-expanded="false"><span class="fas fa-list fa-fw" aria-hidden="true"></span><span class="sidebar-item-title">Menus</span></a><span class="menu-dashboard"><a href="/joomla4x/administrator/index.php?option=com_cpanel&amp;view=cpanel&amp;dashboard=menus"><span class="fas fa-th-large" title="Menus Dashboard" aria-hidden="true"></span><span class="sr-only">Menus Dashboard</span></a></span><ul id="collapse2" class="collapse-level-1 mm-collapse">
                            <li class="item item-level-2"><a class="no-dropdown" href="index.php?option=com_menus&amp;view=menus" aria-label="Manage"><span class="sidebar-item-title">Manage</span></a></li>
                            <li class="divider item-level-2" role="presentation"><span></span></li>
                            <li class="item item-level-2"><a class="no-dropdown" href="index.php?option=com_menus&amp;view=items&amp;menutype=" aria-label="All Menu Items"><span class="sidebar-item-title">All Menu Items</span></a></li>
                            <li class="menuitem-group item-level-2" role="presentation"><span class="sidebar-item-title">Site</span></li>
                            <li class="item item-level-2"><a class="no-dropdown" href="index.php?option=com_menus&amp;view=items&amp;menutype=mainmenu" aria-label="Main Menu "><span class="sidebar-item-title">Main Menu </span><span class="home-image fas fa-star" aria-hidden="true"></span><span class="sr-only">Default</span></a><span class="menu-quicktask"><a href="index.php?option=com_menus&amp;task=item.add&amp;menutype=mainmenu"><span class="fas fa-plus" title="Add Site Menu Item" aria-hidden="true"></span><span class="sr-only">Add Site Menu Item</span></a></span></li>
                        </ul>
                    </li>
                    <li class="item parent item-level-1"><a class="has-arrow" href="#" aria-label="Components" aria-expanded="false"><span class="fas fa-puzzle-piece fa-fw" aria-hidden="true"></span><span class="sidebar-item-title">Components</span></a><span class="menu-dashboard"><a href="/joomla4x/administrator/index.php?option=com_cpanel&amp;view=cpanel&amp;dashboard=components"><span class="fas fa-th-large" title="Components Dashboard" aria-hidden="true"></span><span class="sr-only">Components Dashboard</span></a></span><ul id="collapse3" class="collapse-level-1 mm-collapse">
                            <li class="item parent item-level-2"><a class="has-arrow" href="index.php?option=com_banners" aria-label="Banners" aria-expanded="false"><span class="sidebar-item-title">Banners</span></a><ul id="menu-2" class="mm-collapse collapse-level-2">
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_banners&amp;view=banners" aria-label="Banners"><span class="sidebar-item-title">Banners</span></a></li>
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_categories&amp;view=categories&amp;extension=com_banners" aria-label="Categories"><span class="sidebar-item-title">Categories</span></a></li>
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_banners&amp;view=clients" aria-label="Clients"><span class="sidebar-item-title">Clients</span></a></li>
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_banners&amp;view=tracks" aria-label="Tracks"><span class="sidebar-item-title">Tracks</span></a></li>
                                </ul>
                            </li>
                            <li class="item parent item-level-2"><a class="has-arrow" href="index.php?option=com_contact" aria-label="Contacts" aria-expanded="false"><span class="sidebar-item-title">Contacts</span></a><ul id="menu-7" class="mm-collapse collapse-level-2">
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_contact&amp;view=contacts" aria-label="Contacts"><span class="sidebar-item-title">Contacts</span></a></li>
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_categories&amp;view=categories&amp;extension=com_contact" aria-label="Categories"><span class="sidebar-item-title">Categories</span></a></li>
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_fields&amp;context=com_contact.contact" aria-label="Fields"><span class="sidebar-item-title">Fields</span></a></li>
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_fields&amp;view=groups&amp;context=com_contact.contact" aria-label="Field Groups"><span class="sidebar-item-title">Field Groups</span></a></li>
                                </ul>
                            </li>
                            <li class="item item-level-2"><a class="no-dropdown" href="index.php?option=com_associations&amp;view=associations" aria-label="Multilingual Associations"><span class="sidebar-item-title">Multilingual Associations</span></a></li>
                            <li class="item parent item-level-2"><a class="has-arrow" href="index.php?option=com_newsfeeds" aria-label="News Feeds" aria-expanded="false"><span class="sidebar-item-title">News Feeds</span></a><ul id="menu-10" class="mm-collapse collapse-level-2">
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_newsfeeds&amp;view=newsfeeds" aria-label="Feeds"><span class="sidebar-item-title">Feeds</span></a></li>
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_categories&amp;view=categories&amp;extension=com_newsfeeds" aria-label="Categories"><span class="sidebar-item-title">Categories</span></a></li>
                                </ul>
                            </li>
                            <li class="item parent item-level-2"><a class="has-arrow" href="index.php?option=com_rsgallery2&amp;view=rsgallery2" aria-label="RSGallery2" aria-expanded="false"><span class="sidebar-item-title">RSGallery2</span></a><ul id="menu-109" class="mm-collapse collapse-level-2">
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_rsgallery2" aria-label="Control panel"><span class="sidebar-item-title">Control panel</span></a></li>
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_rsgallery2&amp;view=galleries" aria-label="Galleries"><span class="sidebar-item-title">Galleries</span></a></li>
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_rsgallery2&amp;view=upload" aria-label="Upload"><span class="sidebar-item-title">Upload</span></a></li>
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_rsgallery2&amp;view=images" aria-label="Images"><span class="sidebar-item-title">Images</span></a></li>
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_rsgallery2&amp;view=config&amp;task=config.edit" aria-label="Configuration"><span class="sidebar-item-title">Configuration</span></a></li>
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_rsgallery2&amp;view=maintenance" aria-label="Maintenance"><span class="sidebar-item-title">Maintenance</span></a></li>
                                </ul>
                            </li>
                            <li class="item parent item-level-2"><a class="has-arrow" href="index.php?option=com_finder&amp;view=index" aria-label="Smart Search" aria-expanded="false"><span class="sidebar-item-title">Smart Search</span></a><ul id="menu-13" class="mm-collapse collapse-level-2">
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_finder&amp;view=index" aria-label="Index"><span class="sidebar-item-title">Index</span></a></li>
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_finder&amp;view=maps" aria-label="Content Maps"><span class="sidebar-item-title">Content Maps</span></a></li>
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_finder&amp;view=filters" aria-label="Filters"><span class="sidebar-item-title">Filters</span></a></li>
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_finder&amp;view=searches" aria-label="Statistics"><span class="sidebar-item-title">Statistics</span></a></li>
                                </ul>
                            </li>
                            <li class="item item-level-2"><a class="no-dropdown" href="index.php?option=com_tags&amp;view=tags" aria-label="Tags"><span class="sidebar-item-title">Tags</span></a></li>
                        </ul>
                    </li>
                    <li class="item parent item-level-1"><a class="has-arrow" href="#" aria-label="Users" aria-expanded="false"><span class="fas fa-users fa-fw" aria-hidden="true"></span><span class="sidebar-item-title">Users</span></a><span class="menu-dashboard"><a href="/joomla4x/administrator/index.php?option=com_cpanel&amp;view=cpanel&amp;dashboard=users"><span class="fas fa-th-large" title="Users Dashboard" aria-hidden="true"></span><span class="sr-only">Users Dashboard</span></a></span><ul id="collapse4" class="collapse-level-1 mm-collapse">
                            <li class="item item-level-2"><a class="no-dropdown" href="index.php?option=com_users&amp;view=users" aria-label="Manage"><span class="sidebar-item-title">Manage</span></a><span class="menu-quicktask"><a href="index.php?option=com_users&amp;task=user.add"><span class="fas fa-plus" title="Add User" aria-hidden="true"></span><span class="sr-only">Add User</span></a></span></li>
                            <li class="item item-level-2"><a class="no-dropdown" href="index.php?option=com_users&amp;view=groups" aria-label="Groups"><span class="sidebar-item-title">Groups</span></a></li>
                            <li class="item item-level-2"><a class="no-dropdown" href="index.php?option=com_users&amp;view=levels" aria-label="Access Levels"><span class="sidebar-item-title">Access Levels</span></a></li>
                            <li class="divider item-level-2" role="presentation"><span></span></li>
                            <li class="item item-level-2"><a class="no-dropdown" href="index.php?option=com_fields&amp;view=fields&amp;context=com_users.user" aria-label="Fields"><span class="sidebar-item-title">Fields</span></a></li>
                            <li class="item item-level-2"><a class="no-dropdown" href="index.php?option=com_fields&amp;view=groups&amp;context=com_users.user" aria-label="Field Groups"><span class="sidebar-item-title">Field Groups</span></a></li>
                            <li class="divider item-level-2" role="presentation"><span></span></li>
                            <li class="item item-level-2"><a class="no-dropdown" href="index.php?option=com_users&amp;view=notes" aria-label="User Notes"><span class="sidebar-item-title">User Notes</span></a></li>
                            <li class="item item-level-2"><a class="no-dropdown" href="index.php?option=com_categories&amp;view=categories&amp;extension=com_users" aria-label="User Note Categories"><span class="sidebar-item-title">User Note Categories</span></a></li>
                            <li class="divider item-level-2" role="presentation"><span></span></li>
                            <li class="item parent item-level-2"><a class="has-arrow" href="#" aria-label="Privacy" aria-expanded="false"><span class="sidebar-item-title">Privacy</span></a><span class="menu-dashboard"><a href="/joomla4x/administrator/index.php?option=com_cpanel&amp;view=cpanel&amp;dashboard=privacy"><span class="fas fa-th-large" title="Privacy Dashboard" aria-hidden="true"></span><span class="sr-only">Privacy Dashboard</span></a></span><ul class="mm-collapse collapse-level-2">
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_privacy&amp;view=requests" aria-label="Requests"><span class="sidebar-item-title">Requests</span></a></li>
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_privacy&amp;view=capabilities" aria-label="Capabilities"><span class="sidebar-item-title">Capabilities</span></a></li>
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_privacy&amp;view=consents" aria-label="Consents"><span class="sidebar-item-title">Consents</span></a></li>
                                </ul>
                            </li>
                            <li class="item item-level-2"><a class="no-dropdown" href="index.php?option=com_actionlogs&amp;view=actionlogs" aria-label="User Actions Log"><span class="sidebar-item-title">User Actions Log</span></a></li>
                            <li class="divider item-level-2" role="presentation"><span></span></li>
                            <li class="item item-level-2"><a class="no-dropdown" href="index.php?option=com_users&amp;view=mail" aria-label="Mass Mail Users"><span class="sidebar-item-title">Mass Mail Users</span></a></li>
                            <li class="item parent item-level-2"><a class="has-arrow" href="index.php?option=com_messages" aria-label="Messaging" aria-expanded="false"><span class="sidebar-item-title">Messaging</span></a><ul class="mm-collapse collapse-level-2">
                                    <li class="item item-level-3"><a class="no-dropdown" href="index.php?option=com_messages&amp;view=messages" aria-label="Private Messages"><span class="sidebar-item-title">Private Messages</span></a><span class="menu-quicktask"><a href="index.php?option=com_messages&amp;task=message.add"><span class="fas fa-plus" title="New Item" aria-hidden="true"></span><span class="sr-only">New Item</span></a></span></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="item item-level-1"><a class="no-dropdown" href="index.php?option=com_cpanel&amp;view=cpanel&amp;dashboard=system" aria-label="System"><span class="fas fa-wrench fa-fw" aria-hidden="true"></span><span class="sidebar-item-title">System</span></a></li>
                    <li class="item item-level-1"><a class="no-dropdown" href="index.php?option=com_cpanel&amp;view=cpanel&amp;dashboard=help" aria-label="Help"><span class="fas fa-info-circle fa-fw" aria-hidden="true"></span><span class="sidebar-item-title">Help</span></a></li>
                </ul></nav>
 -->
    </div>
    </div>


    <input type="hidden" name="task" value=""/>
    <?php
    echo HTMLHelper::_('form.token'); ?>
</form>


