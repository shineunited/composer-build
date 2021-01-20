<?php

namespace ShineUnited\ComposerBuild;

use ShineUnited\ComposerBuild\Command\CommandProvider;
use ShineUnited\ComposerBuild\Task\TaskManager;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Plugin\Capable;
use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;


class Plugin implements PluginInterface, Capable {
	private $composer;
	private $io;
	private $taskManager;

	public function activate(Composer $composer, IOInterface $io) {
		$this->composer = $composer;
		$this->io = $io;
		$this->taskManager = false;
	}

	public function deactivate(Composer $composer, IOInterface $io) {
		// do nothing
	}

	public function uninstall(Composer $composer, IOInterface $io) {
		// do nothing
	}

	public function getCapabilities() {
		return array(
			CommandProviderCapability::class => CommandProvider::class
		);
	}

	public function getTaskManager() {
		if(!$this->taskManager instanceof TaskManager) {
			$this->taskManager = new TaskManager($this->composer, $this->io);
		}

		return $this->taskManager;
	}
}
