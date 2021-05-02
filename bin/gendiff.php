#!/usr/bin/env php
<?php

$autoloadPath1 = __DIR__ . '/../../../autoload.php';
$autoloadPath2 = __DIR__ . '/../vendor/autoload.php';


if (file_exists($autoloadPath1)) {
    require_once $autoloadPath1;
} else {
    require_once $autoloadPath2;
}

const DOC = <<<DOC
Generate diff

Usage:
    gendiff (-h|--help)
    gendiff (-v|--version)
    gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
    -h --help                  Show this screen
    -v --version               Show version
    -f --format <fmt>          Report format [default: stylish]
DOC;

$args = \Docopt::handle(DOC, array('version' => '1.0'));
