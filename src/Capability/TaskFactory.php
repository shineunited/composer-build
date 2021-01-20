<?php

namespace ShineUnited\ComposerBuild\Capability;

use Composer\Plugin\Capability\Capability;


interface TaskFactory extends Capability {

	public function handlesType($type);

	public function createTask($type, $name, array $config = array());
}
