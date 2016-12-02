#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Ontic\NoFraud\Commands\CreateUserCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new CreateUserCommand());
$application->run();
