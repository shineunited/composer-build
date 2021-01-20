<?php

namespace ShineUnited\ComposerBuild\Task;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class CompositeTask extends Task {
	private $tasks;

	public function __construct($name, array $config = array()) {
		$this->tasks = array();

		parent::__construct($name, $config);
	}

	public function addTask(Task $task) {
		$this->tasks[] = $task;
	}

	public function execute(InputInterface $input, OutputInterface $output) {
		foreach($this->tasks as $task) {
			$task->setApplication($this->getApplication());

			$returnCode = $task->run(
				new ArrayInput(array()),
				$output
			);

			$task->setApplication(null);

			if($returnCode) {
				// exit if return code is greater than 0
				return $returnCode;
			}
		}
	}
}
