<?php

namespace ShineUnited\ComposerBuild\Command;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;


class CommandProvider implements CommandProviderCapability {
	use TaskManagerTrait;

	private $composer;
	private $io;

	public function __construct($arguments) {
		if(!isset($arguments['composer'])) {
			throw new \InvalidArgumentException('Missing expected "composer" argument');
		}

		if(!isset($arguments['io'])) {
			throw new \InvalidArgumentException('Missing expected "io" argument');
		}

		if(!$arguments['composer'] instanceof Composer) {
			throw new \UnexpectedValueException('"composer" argument must be instance of ' . Composer::class);
		}

		if(!$arguments['io'] instanceof IOInterface) {
			throw new \UnexpectedValueException('"io" argument must be instance of ' . IOInterface::class);
		}

		$this->composer = $arguments['composer'];
		$this->io = $arguments['io'];
	}

	public function getComposer($required = true, $disablePlugins = null) {
		return $this->composer;
	}

	public function getIO() {
		return $this->io;
	}

	public function getCommands() {
		$composer = $this->getComposer();
		$package = $this->composer->getPackage();
		$extra = $package->getExtra();

		$taskManager = $this->getTaskManager();

		if(!isset($extra['build']) || !is_array($extra['build'])) {
			return array();
		}

		$tasks = array();
		foreach($extra['build'] as $name => $config) {
			$tasks[] = $taskManager->createTask($name, $config);
		}

		return $tasks;
	}
}
