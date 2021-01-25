<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

JHtml::_('behavior.caption');

$dispatcher = JEventDispatcher::getInstance();

$container->category->text = $container->category->description;
$dispatcher->trigger('onContentPrepare', array($container->category->extension . '.categories', &$container->category, &$container->params, 0));
$container->category->description = $container->category->text;

$results = $dispatcher->trigger('onContentAfterTitle', array($container->category->extension . '.categories', &$container->category, &$container->params, 0));
$afterDisplayTitle = trim(implode("\n", $results));

$results = $dispatcher->trigger('onContentBeforeDisplay', array($container->category->extension . '.categories', &$container->category, &$container->params, 0));
$beforeDisplayContent = trim(implode("\n", $results));

$results = $dispatcher->trigger('onContentAfterDisplay', array($container->category->extension . '.categories', &$container->category, &$container->params, 0));
$afterDisplayContent = trim(implode("\n", $results));

?>
<div class="blog<?php echo $container->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Blog">
	<?php if ($container->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1> <?php echo $container->escape($container->params->get('page_heading')); ?> </h1>
		</div>
	<?php endif; ?>

	<?php if ($container->params->get('show_category_title', 1) or $container->params->get('page_subheading')) : ?>
		<h2> <?php echo $container->escape($container->params->get('page_subheading')); ?>
			<?php if ($container->params->get('show_category_title')) : ?>
				<span class="subheading-category"><?php echo $container->category->title; ?></span>
			<?php endif; ?>
		</h2>
	<?php endif; ?>
	<?php echo $afterDisplayTitle; ?>

	<?php if ($container->params->get('show_cat_tags', 1) && !empty($container->category->tags->itemTags)) : ?>
		<?php $container->category->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
		<?php echo $container->category->tagLayout->render($container->category->tags->itemTags); ?>
	<?php endif; ?>

	<?php if ($beforeDisplayContent || $afterDisplayContent || $container->params->get('show_description', 1) || $container->params->def('show_description_image', 1)) : ?>
		<div class="category-desc clearfix">
			<?php if ($container->params->get('show_description_image') && $container->category->getParams()->get('image')) : ?>
                <div class="sda_dropdown">
                    <img src="<?php echo $container->category->getParams()->get('image'); ?>" alt="<?php echo htmlspecialchars($container->category->getParams()->get('image_alt'), ENT_COMPAT, 'UTF-8'); ?>"/>
                    <div class="dropdown-content">
                        <img alt="<?php echo htmlspecialchars($container->category->getParams()->get('image_alt'), ENT_COMPAT, 'UTF-8'); ?>" src="<?php echo $container->category->getParams()->get('image'); ?>" />
                        <div class="desc"><?php echo htmlspecialchars($container->category->getParams()->get('image_alt'), ENT_COMPAT, 'UTF-8'); ?></div>
                    </div>
                </div>
				<?php echo $beforeDisplayContent; ?>
				<?php if ($container->params->get('show_description') && $container->category->description) : ?>
					<?php echo JHtml::_('content.prepare', $container->category->description, '', 'com_content.category'); ?>
				<?php endif; ?>
				<?php echo $afterDisplayContent; ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if (empty($container->lead_items) && empty($container->link_items) && empty($container->intro_items)) : ?>
		<?php if ($container->params->get('show_no_articles', 1)) : ?>
			<p><?php echo JText::_('COM_CONTENT_NO_ARTICLES'); ?></p>
		<?php endif; ?>
	<?php endif; ?>

	<?php $leadingcount = 0; ?>
	<?php if (!empty($container->lead_items)) : ?>
		<div class="items-leading clearfix">
			<?php foreach ($container->lead_items as &$item) : ?>
				<div class="leading-<?php echo $leadingcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>"
					itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
					<?php
					$container->item = &$item;
					echo $container->loadTemplate('item');
					?>
				</div>
				<?php $leadingcount++; ?>
			<?php endforeach; ?>
		</div><!-- end items-leading -->
	<?php endif; ?>

	<?php
	$introcount = count($container->intro_items);
	$counter = 0;
	?>

	<?php if (!empty($container->intro_items)) : ?>
		<?php foreach ($container->intro_items as $key => &$item) : ?>
			<?php $rowcount = ((int) $key % (int) $container->columns) + 1; ?>
			<?php if ($rowcount === 1) : ?>
				<?php $row = $counter / $container->columns; ?>
				<div class="items-row cols-<?php echo (int) $container->columns; ?> <?php echo 'row-' . $row; ?> row-fluid clearfix">
			<?php endif; ?>
			<div class="span<?php echo round(12 / $container->columns); ?>">
				<div class="item column-<?php echo $rowcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>"
					itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
					<?php
					$container->item = &$item;
					echo $container->loadTemplate('item');
					?>
				</div>
				<!-- end item -->
				<?php $counter++; ?>
			</div><!-- end span -->
			<?php if (($rowcount == $container->columns) or ($counter == $introcount)) : ?>
				</div><!-- end row -->
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>

	<?php if (!empty($container->link_items)) : ?>
		<div class="items-more">
			<?php echo $container->loadTemplate('links'); ?>
		</div>
	<?php endif; ?>

	<?php if ($container->maxLevel != 0 && !empty($container->children[$container->category->id])) : ?>
		<div class="cat-children">
			<?php if ($container->params->get('show_category_heading_title_text', 1) == 1) : ?>
				<h3> <?php echo JText::_('JGLOBAL_SUBCATEGORIES'); ?> </h3>
			<?php endif; ?>
			<?php echo $container->loadTemplate('children'); ?> </div>
	<?php endif; ?>
	<?php if (($container->params->def('show_pagination', 1) == 1 || ($container->params->get('show_pagination') == 2)) && ($container->pagination->get('pages.total') > 1)) : ?>
		<div class="pagination">
			<?php if ($container->params->def('show_pagination_results', 1)) : ?>
				<p class="counter pull-right"> <?php echo $container->pagination->getPagesCounter(); ?> </p>
			<?php endif; ?>
			<?php echo $container->pagination->getPagesLinks(); ?> </div>
	<?php endif; ?>
</div>
