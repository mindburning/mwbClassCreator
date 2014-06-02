<?php

include_once 'mwb/mwbReader.php';

$d = new mwbReader();
$d->outputFolder = __DIR__ . "/model/";
$d->modelPrefix = '';
$d->renderFile('./model.mwb', 'phpclass.php');

echo "model created";