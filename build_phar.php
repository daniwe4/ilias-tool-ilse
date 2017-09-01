<?php

/* Copyright (c) 2017 Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

$base_dir = __DIR__;
$build_dir = __DIR__;
$phar_name = "ilse.phar";
$phar_path = "$build_dir/$phar_name";

if (file_exists($phar_path)) {
	unlink($phar_path);
}

$phar = new Phar
	( $phar_path
	, FilesystemIterator::CURRENT_AS_FILEINFO
		| FilesystemIterator::KEY_AS_FILENAME
	, $phar_name
	);

$phar->buildFromDirectory($base_dir);

$phar->setStub(<<<STUB
#!/usr/bin/env php
<?php
Phar::mapPhar();
include "phar://$phar_name/ilse.php";
__HALT_COMPILER();
STUB
);

chmod($phar_path, 0755);
