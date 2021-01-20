<?php

namespace ShineUnited\ComposerBuild\Task;

use ShineUnited\ComposerBuild\Capability\TaskFactory;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capability\Cability;


class TaskManager {
	private $composer;
	private $io;
	private $factories;

	public function __construct(Composer $composer, IOInterface $io) {
		$this->composer = $composer;
		$this->io = $io;

		$pm = $this->composer->getPluginManager();
		$this->factories = $pm->getPluginCapabilities(
			TaskFactory::class,
			array(
				'composer' => $this->composer,
				'io'       => $this->io
			)
		);
	}

	public function addFactory(TaskFactoryInterface $factory) {
		$this->factories[] = $factory;
	}

	public function findFactory($type) {
		foreach($this->factories as $factory) {
			if($factory->handlesType($type)) {
				return $factory;
			}
		}

		throw new \RuntimeException('Unable to find factory for task type "' . $type . '"');
	}

	public function createTask($name, $config) {
		if(!$config) {
			throw new \UnexpectedValueException('Task config must be a string or an array');
		}

		if(is_string($config)) {
			// alias task
			return new AliasTask($name, array('task' => $config));
		}

		if(!is_array($config)) {
			throw new \UnexpectedValueException('Task config must be a string or an array');
		}

		// non-assoc array,
		if(array_keys($config) === range(0, count($config) - 1)) {
			// composite task
			$task = new CompositeTask($name);
			foreach($config as $subconfig) {
				$subname = uniqid($name . '-');
				$subtask = $this->createTask($subname, $subconfig);

				$task->addTask($subtask);
			}

			return $task;
		}

		if(!isset($config['task'])) {
			// task not defined, error?
			throw new \UnexpectedValueException('Task config must contain "task" parameter');
		}

		$type = $config['task'];
		unset($config['task']);

		$factory = $this->findFactory($type);

		$task = $factory->createTask($type, $name, $config);

		if(!$task instanceof Task) {
			throw new \UnexpectedValueException('Plugin capability ' . get_class($factory) . ' returned an invalid value, we expected an instance of ' . Task::class);
		}

		return $task;
	}
}
