<?php

use FOF30\Container\Container;

$container = Container::getInstance('com_sdajem');

//$lastModified = array();

if (isset($container)) {
	/** @var \Sda\Jem\Site\Model\Category $category */
	//$category = $container->factory->model('Category');
	/** @var \Sda\Jem\Site\Model\Attendee $attendee */
	//$attendee = $container->factory->model('Attendee');
	/** @var \Sda\Jem\Site\Model\Comment $comment */
	//$comment = $container->factory->model('Comment');
	/** @var \Sda\Jem\Site\Model\Location $location */
	//$location = $container->factory->model('Location');
	/** @var \Sda\Jem\Site\Model\Event $event */
	$event = $container->factory->model('Event');

	/** @var \Joomla\CMS\Date\Date $lastModified */
	$lastModified = $event->getLastModified();

	//$lastModified = array_add($lastModified, 'Attendee', $attendee->getLastModified());
	//$lastModified = array_add($lastModified, 'Comment', $comment->getLastModified());
	//$lastModified = array_add($lastModified, 'Location', $location->getLastModified());
	//$lastModified = array_add($lastModified, 'Event', $event->getLastModified());
	//$lastModified = array_add($lastModified, 'int-Test', 15);

	echo json_encode($lastModified->toISO8601(),JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
} else {
    echo "Fail";
}

