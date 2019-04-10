<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use FOF30\Container\Container;

$currentUser = Factory::getUser()->id;

/** @var \Sda\Jem\Site\View\Event\Html      $this       */
/** @var \Sda\Jem\Site\Model\Event          $event      */

$event = $this->getModel('Event');
$this->addCssFile('media://com_sdajem/css/style.css');
$this->addJavascriptFile('media/com_sdajem/js/register.js');

$subscribed = false;

/** @var \Sda\Jem\Admin\Model\Mailing $mailing */
$mailing = Container::getInstance('com_sdajem')->factory->model('Mailing');
$mailing->getSubscriptionForUserAndEvent($event->sdajem_event_id, $currentUser);

if ($mailing)
{
	$subscribed = (bool) $mailing->subscribed;
}

if ($subscribed)
{
	$html = "<button id=\"subscribe\" type=\"button\" form=\"attendeeForm\" name=\"subscribe\" value=\"0\" onclick=\"subscribeAjax(0)\">" .
		"<i class=\"fas fa-envelope-open-text\" aria-hidden='true'></i> " .
		Text::_('COM_SDAJEM_UNSUBSCRIBE') .
		"</button>";
}
else
{
	$html = "<button id=\"subscribe\" type=\"button\" form=\"attendeeForm\" name=\"subscribe\" value=\"1\" onclick=\"subscribeAjax(1)\">" .
		"<i class=\"fas fa-envelope-open-text\" aria-hidden='true'></i> " .
		Text::_('COM_SDAJEM_SUBSCRIBE') .
		"</button>";
}

echo $html;
