<?php
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style( 'ryu', get_template_directory_uri() . '/style.css' );
});

