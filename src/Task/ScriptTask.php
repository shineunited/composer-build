<?php

namespace ShineUnited\ComposerBuild\Task;

use ShineUnited\ComposerBuild\Task\Task;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Composer\Command\RunScriptCommand;
use Composer\IO\IOInterface;


class ScriptTask extends Task {
	private $passthruArgs;
	private $passthruOpts;

	public function configure() {

		$runCommand = new RunScriptCommand();
		$runDefinition = $runCommand->getDefinition();

		$this->passthruArgs = array();
		foreach($runDefinition->getArguments() as $argument) {
			$this->passthruArgs[] = $argument->getName();
		}

		$this->passthruOpts = array();
		foreach($runDefinition->getOptions() as $option) {
			$this->passthruOpts[] = $option->getName();
		}

		$this->setDefinition($runDefinition);
	}

	public function execute(InputInterface $input, OutputInterface $output) {
		$io = $this->getIO();

		$runCommand = $this->getApplication()->find('run-script');
		$runDefinition = $runCommand->getDefinition();

		$runInput = new ArrayInput(array(
			'script' => $input->getArgument('script')
		));

		$runInput->bind($runDefinition);

		foreach($this->passthruArgs as $name) {
			if($name == 'script') {
				continue;
			}

			$runInput->setArgument($name, $input->getArgument($name));
		}

		foreach($this->passthruOpts as $name) {
			$runInput->setOption($name, $input->getOption($name));
		}

		return $runCommand->run($runInput, $output);
	}
}
