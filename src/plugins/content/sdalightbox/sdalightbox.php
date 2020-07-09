<?php

/**
 * @version 1.0.0
 * @package SDA
 * @subpackage Sda Mailer Plugin
 * @copyright (C) 2018 Alexander Bahlo
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;

/**
 * Send Mail at defined events.
 *
 * @package     Sda
 *
 * @since       1.0
 */
class plgContentSdalightbox extends CMSPlugin
{
	function onContentPrepare($context, &$article, &$params, $limitstart)
	{
		$imgPattern = '/(<img[^>]*>)/';
		$srcPattern = '/(src)=[\'"](?P<src>[^\'"]*)/';
		$altPattern = '/(alt)=[\'"](?P<alt>[^\'"]*)/';
		$text = array();
		$gallery = array();
		$galleryString = '';

		if ($context == 'com_content.article') {
			$text = $chars = preg_split($imgPattern, $article->text, -1, PREG_SPLIT_DELIM_CAPTURE);

			$itemCount = 0;
			foreach ($text as &$item) {
				preg_match($srcPattern, $item, $srcMatch);
				preg_match($altPattern, $item, $altMatch);

				$matches = array_merge($srcMatch, $altMatch);

				if (count($matches) > 0) {
					$item = '<a href="#sda_img' . $itemCount .'">' . $item .'</a>';
					$match['src'] = $matches['src'];
					$match['alt'] = $matches['alt'];
					array_push($gallery, $match);
					$itemCount++;
				}
			}

			$itemCount = 0;
			$prevElemet = 0;
			$lastElement = count($gallery);

			foreach ($gallery as $value) {
				$lightbox = '<div class="sda_lightbox" id="sda_img' . $itemCount . '">';

				$lightbox = $lightbox . '<a href="#_" class="sda_btn-close">X</a>' .
							'<img src="' . $value['src'] . '" alt="' . $value['alt'] . '">' .
							'<p>' . $value['alt'] . '</p>';

				$lightbox = $lightbox . '<div class="sda_lightbox_nav">';

				if ($itemCount == 0) {
					$lightbox = $lightbox . '<a href="#sda_img' . ($lastElement-1) . '" class="sda_light-btn sda_btn-prev">' . Text::_('<<') . '</a>';
				} else {
					$lightbox = $lightbox . '<a href="#sda_img' . $prevElemet . '" class="sda_light-btn sda_btn-prev">' . Text::_('<<') . '</a>';
					$prevElemet++;
				}

				$itemCount++;

				if ($itemCount < $lastElement) {
					$lightbox = $lightbox . '<a href="#sda_img' . $itemCount . '" class="sda_light-btn sda_btn-next">' . Text::_('>>') . '</a>';
				} else {
					$lightbox = $lightbox . '<a href="#sda_img0" class="sda_light-btn sda_btn-next">' . Text::_('>>') . '</a>';
				}

				$lightbox = $lightbox . '</div></div>';

				$galleryString = $galleryString . $lightbox;
			}

			$article->text = implode(' ', $text) . $galleryString;
		}
	}
}