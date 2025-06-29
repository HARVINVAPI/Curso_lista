<?php
defined('MOODLE_INTERNAL') || die();

$definitions = array(
    'courses' => array(
        'mode' => cache_store::MODE_APPLICATION,
        'component' => 'block_curso_lista',
        'area' => 'courses',
        'simplekeys' => true,
        'simpledata' => false,
        'ttl' => 300, // 5 minutos
        'staticacceleration' => true,
        'staticaccelerationsize' => 10,
    ),
    'progress' => array(
        'mode' => cache_store::MODE_SESSION,
        'component' => 'block_curso_lista',
        'area' => 'progress',
        'simplekeys' => true,
        'simpledata' => true,
        'ttl' => 600, // 10 minutos
        'staticacceleration' => true,
        'staticaccelerationsize' => 20,
    ),
    'images' => array(
        'mode' => cache_store::MODE_APPLICATION,
        'component' => 'block_curso_lista',
        'area' => 'images',
        'simplekeys' => true,
        'simpledata' => true,
        'ttl' => 3600, // 1 hora
        'staticacceleration' => true,
        'staticaccelerationsize' => 50,
    )
);