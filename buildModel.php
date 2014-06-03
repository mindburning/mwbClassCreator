<?php

include_once 'mwb/mwbReader.php';

mwbReader::getInstance(__DIR__ . "/model/")
		->renderFile('./model.mwb');

echo "model created";