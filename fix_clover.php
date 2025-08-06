<?php
$file = 'build/logs/clover.xml';
$dom = new DOMDocument();
$dom->load($file);
foreach ($dom->getElementsByTagName('file') as $fileNode) {
    $name = $fileNode->getAttribute('name');
    if (strpos($name, '/var/www/html/') === 0) {
        $relative = substr($name, strlen('/var/www/html/'));
        $fileNode->setAttribute('name', $relative);
    }
}
$dom->save($file);
