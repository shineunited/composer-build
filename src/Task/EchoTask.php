<?php

namespace ShineUnited\ComposerBuild\Task;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Composer\IO\IOInterface;


class EchoTask extends Task {

	public function configure() {
		$this->addArgument(
			'msg', // name
			InputArgument::REQUIRED | InputArgument::IS_ARRAY, // mode
			'Message(s) to echo', // description
			null // default
		);

		$this->addOption(
			'no-newline', // name
			null, // shortcut
			InputOption::VALUE_NONE, // mode
			'Do not print the trailing newline character', // description
			null
		);

		$this->addOption(
			'verbosity', // name
			null, // shortcut
			InputOption::VALUE_REQUIRED, // mode
			'Verbosity level of echo messages, must be "normal", "verbose", "veryverbose" or "debug"',
			'normal'
		);
	}

	protected function translateVerbosity($string) {
		$string = preg_replace('/[\s-_]+/', '', $string);
		$string = strtolower($string);
		switch($string) {
			case 'vvv':
			case 'debug':
				return IOInterface::DEBUG;
				break;
			case 'vv':
			case 'very':
			case 'veryverbose':
				return IOInterface::VERY_VERBOSE;
				break;
			case 'v':
			case 'verbose':
				return IOInterface::VERBOSE;
				break;
			default:
			case 'normal':
				return IOInterface::NORMAL;
				break;
			case 'q':
			case 'quiet':
				return IOInterface::QUIET;
				break;
		}
	}

	public function execute(InputInterface $input, OutputInterface $output) {
		$io = $this->getIO();
		$messages = $input->getArgument('msg');

		$newline = true;
		if($input->getOption('no-newline')) {
			$newline = false;
		}

		$verbosity = $this->translateVerbosity($input->getOption('verbosity'));

		$io->write($messages, $newline, $verbosity);
	}
}
