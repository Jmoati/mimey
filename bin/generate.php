#!/usr/bin/env php
<?php

use Mimey\MimeMappingGenerator;

$projectDir = dirname(__DIR__);

require_once $projectDir.'/vendor/autoload.php';

$mime_types_custom_text = file_get_contents($projectDir.'/mime.types.custom');
$mime_types_text = file_get_contents($projectDir.'/mime.types');

$generator = new MimeMappingGenerator($mime_types_custom_text.PHP_EOL.$mime_types_text);
$mapping_code = $generator->generateMappingCode();

file_put_contents($projectDir.'/mime.types.php', $mapping_code);
