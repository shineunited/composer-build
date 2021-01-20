<?php

namespace ShineUnited\ComposerBuild\Task;

use ShineUnited\ComposerBuild\Task\Task;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Composer\IO\IOInterface;


class AliasTask extends Task {

	public function configure() {
		$this->addArgument(
			'name', // name
			InputArgument::REQUIRED, // mode
			'Task to execute', // description
			null // default
		);
	}

	public function execute(InputInterface $input, OutputInterface $output) {
		$io = $this->getIO();
		$taskName = $input->getArgument('name');

		$task = $this->getApplication()->find('build:' . $taskName);
		$taskInput = new ArrayInput(array());

		return $task->run(
			new ArrayInput(array()),
			$output
		);
	}
}
