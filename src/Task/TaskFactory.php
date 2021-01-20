<?php

namespace ShineUnited\ComposerBuild\Task;

use ShineUnited\ComposerBuild\Capability\TaskFactory as TaskFactoryCapability;


class TaskFactory implements TaskFactoryCapability {

	public function handlesType($type) {
		$types = array(
			'alias',
			'script',
			'echo'
		);

		return in_array($type, $types);
	}

	public function createTask($type, $name, array $config = array()) {
		switch($type) {
			case 'alias':
				return new AliasTask($name, $config);
			case 'script':
				return new ScriptTask($name, $config);
			case 'echo':
				return new EchoTask($name, $config);
			default:
				return false;
		}
	}
}
