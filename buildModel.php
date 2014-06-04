<?php

include_once 'mwb/mwbReader.php';

mwbReader::getInstance(__DIR__ . "/model/")
		->renderFile('./model.mwb');
mwbReader::getInstance(__DIR__ . "/forms/")
		->renderFile('./model.mwb','form.php');

echo "model created";