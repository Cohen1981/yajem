<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/** @var JDocumentHtml $this */

$app  = JFactory::getApplication();
$user = JFactory::getUser();
$doc = JFactory::getDocument();

// Output as HTML5
$this->setHtml5(true);

// Getting params from template
$params = $app->getTemplate(true)->params;

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');

if ($task === 'edit' || $layout === 'form')
{
	$fullWidth = 1;
}
else
{
	$fullWidth = 0;
}

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');

// Add template js
JHtml::_('script', 'template.js', array('version' => 'auto', 'relative' => true));

// Add html5 shiv
JHtml::_('script', 'jui/html5.js', array('version' => 'auto', 'relative' => true, 'conditional' => 'lt IE 9'));

// Add Stylesheets
JHtml::_('stylesheet', $this->baseurl.'/media/jui/css/icomoon.css', array('version' => 'auto', 'relative' => true));
JHtml::_('stylesheet', 'template.css', array('version' => 'auto', 'relative' => true));

// Check for a custom CSS file
JHtml::_('stylesheet', 'user.css', array('version' => 'auto', 'relative' => true));

// Check for a custom js file
JHtml::_('script', 'user.js', array('version' => 'auto', 'relative' => true));

// Load optional RTL Bootstrap CSS
JHtml::_('bootstrap.loadCss', false, $this->direction);

// Logo file or site title param
if ($this->params->get('logoFile'))
{
	$logo = '<img src="' . JUri::root() . $this->params->get('logoFile') . '" alt="' . $sitename . '" class="logo" />';
}
elseif ($this->params->get('sitetitle'))
{
	$logo = '<span class="site-title" title="' . $sitename . '">' . htmlspecialchars($this->params->get('sitetitle'), ENT_COMPAT, 'UTF-8') . '</span>';
}
else
{
	$logo = '<span class="site-title" title="' . $sitename . '">' . $sitename . '</span>';
}

// Check for Modules
$position7ModuleCount = $this->countModules('position-7');
$position8ModuleCount = $this->countModules('position-8');
$positionBreadcrumbModuleCount = $this->countModules('breadcrumb');
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<jdoc:include type="head" />
</head>

<body class="site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($this->direction === 'rtl' ? ' rtl' : '');
?>">
	<!-- Body -->
	<div class="body" id="top">
		<div class="container">

			<!-- Header -->
			<header class="header" role="banner">
				<div class="header-inner clearfix">
					<a class="sda_brand" href="<?php echo $this->baseurl; ?>/">
						<?php echo $logo; ?>
						<?php if ($this->params->get('sitedescription')) : ?>
							<?php echo '<div class="site-description">' . htmlspecialchars($this->params->get('sitedescription'), ENT_COMPAT, 'UTF-8') . '</div>'; ?>
						<?php endif; ?>
					</a>
				</div>
				<?php if ($this->countModules('position-1')) : ?>
                    <nav class="navigation sda_horizontal_menu" role="navigation">
                        <label id="sda_mobile_nav" for="menu_switch" class="sda_switch_label">
                            <i class="fas fa-bars burger_menu" aria-hidden="true"> </i>
                        </label>
                        <input type="checkbox" id="menu_switch" class="sda_hidden sda_switch">
                        <div class="sda_switchable_menu">
                            <jdoc:include type="modules" name="position-1" style="none" />
                        </div>
                    </nav>
				<?php endif; ?>

                <?php if ($this->countModules('position-0') || $this->countModules('breadcrumb')) : ?>
                    <div id="head_last_row" class="sda_content_spacer sda_bottom_rounded">
	                    <?php if ($positionBreadcrumbModuleCount) :?>
                        <div id="breadcrumb_pos" class="sda_horizontal_menu">
                            <jdoc:include type="modules" name="breadcrumb" style="xhtml" />
                        </div>
	                    <?php endif; ?>

	                    <?php if ($this->countModules('position-0')) : ?>
                        <label id="sda_user_label" for="user_login" class="sda_switch_label">
                            <?php if($user->guest) : ?>
                                <i class="far fa-user" aria-hidden="true"></i>
                            <?php endif; ?>
                            <?php if(!$user->guest) : ?>
                                <i class="fas fa-user" aria-hidden="true"></i>
                            <?php endif; ?>
                        </label>
	                    <?php endif; ?>
                    </div>
					<?php if ($this->countModules('position-0')) : ?>
                    <input type="checkbox" id="user_login" class="sda_hidden sda_switch">
                    <div id="user_login_form" class="sda_switchable sda_content_spacer">
                        <jdoc:include type="modules" name="position-0" style="none" />
                    </div>
					<?php endif; ?>

                <?php endif; ?>
			</header>

            <div id="main" class="sda_content_spacer">
                <div class="row-fluid sda_content_grid">
                    <?php if ($position8ModuleCount) : ?>
                        <!-- Begin Sidebar -->
                        <div id="sidebar" class="sda_col sda_col_left">
                            <div class="sidebar-nav">
                                <jdoc:include type="modules" name="position-8" style="xhtml" />
                            </div>
                        </div>
                        <!-- End Sidebar -->
                    <?php endif; ?>
                    <main id="content" role="main" class="sda_col_content">
                        <!-- Begin Content -->
                        <jdoc:include type="modules" name="position-3" style="xhtml" />
                        <jdoc:include type="message" />
                        <jdoc:include type="component" />
                        <div class="clearfix"></div>
                        <jdoc:include type="modules" name="position-2" style="none" />
                        <!-- End Content -->
                    </main>
                    <?php if ($position7ModuleCount) : ?>
                        <input type="checkbox" id="right_pane" class="sda_hidden sda_switch">
                        <label for="right_pane">
                            <i class="fas fa-angle-double-left" aria-hidden="true"></i>
                        </label>
                        <div id="aside" class="sda_col sda_col_right sda_switchable">
                            <!-- Begin Right Sidebar -->
                            <jdoc:include type="modules" name="position-7" style="well" />
                            <!-- End Right Sidebar -->
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Footer -->
            <footer class="footer sda_content_spacer" role="contentinfo">
                <div class="sda_horizontal_menu">
                    <jdoc:include type="modules" name="footer" style="none" />
                </div>
                <p class="pull-right">
                    <a href="#top" id="back-top">
						<?php echo JText::_('TPL_PROTOSTAR_BACKTOTOP'); ?>
                    </a>
                </p>
                <p>
                    &copy; <?php echo date('Y'); ?> <?php echo $sitename; ?>
                </p>
            </footer>

		</div>
        <jdoc:include type="modules" name="debug" style="none" />
	</div>
</body>
</html>
