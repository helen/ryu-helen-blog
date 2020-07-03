<?php
add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_style( 'ryu', get_template_directory_uri() . '/style.css' );
});

add_action( 'pre_get_posts', function( $query ){
	if( $query->is_home() && $query->is_main_query() ) {
		$moblog = get_category_by_slug( 'moblog' )->term_id;
		$query->set( 'category__not_in', [ $moblog ] );
	}
});
