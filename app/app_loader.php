<?php
$base = __DIR__ .'/';

$folders = [
    'Lib',
    'Models',
    'Routes'
];

foreach($folders as $f)
{
    foreach (glob($base . "$f/*.php") as $filename)
    {
        require $filename;
    }
}