<?php

namespace ShineUnited\ComposerBuild\Task;

use Composer\Command\BaseCommand;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


abstract class Task extends BaseCommand {
	private $config;
	private $taskName;

	public function __construct($name, array $config = array()) {
		$this->config = $config;

		parent::__construct($name);
	}

	public function setName($name) {
		$this->taskName = $name;
		$commandName = 'build:' . $name;

		return parent::setName($commandName);
	}

	protected function initialize(InputInterface $input, OutputInterface $output) {
		foreach($this->config as $name => $value) {
			if($input->hasArgument($name)) {
				$input->setArgument($name, $value);
			}

			if($input->hasOption($name)) {
				$input->setOption($name, $value);
			}
		}

		parent::initialize($input, $output);
	}
}
