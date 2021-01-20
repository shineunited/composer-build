<?php

namespace ShineUnited\ComposerBuild\Command;

use ShineUnited\ComposerBuild\Plugin;


trait TaskManagerTrait {

	public function getTaskManager() {
		$composer = $this->getComposer();
		$pm = $composer->getPluginManager();

		foreach($pm->getPlugins() as $plugin) {
			if($plugin instanceof Plugin) {
				return $plugin->getTaskManager();
			}
		}
	}

	abstract public function getComposer($required = true, $disablePlugins = null);

}
