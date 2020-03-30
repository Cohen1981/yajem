<?php


class plgSystemSdalib extends JPlugin
{
	public function onAfterInitialise()
	{
		JLoader::registerNamespace('Sda', JPATH_LIBRARIES);
	}
}
