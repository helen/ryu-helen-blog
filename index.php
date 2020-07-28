<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Ryu
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

		<?php if ( have_posts() ) :
			$moblog = get_category_by_slug( 'moblog' )->term_id;

			if ( is_paged() ) {
				// Get the date of the last post before this one
				$perpage = get_option( 'posts_per_page' );
				$paged = get_query_var('paged');

				$offset = ( $perpage * ( $paged - 1 ) ) - 1;

				$query_args = [
					'category__not_in' => [ $moblog ],
					'offset' => $offset,
					'posts_per_page' => 1,
					'update_post_term_cache' => false,
					'update_post_meta_cache' => false,
					'no_found_rows' => true,
				];

				$prev_post = new WP_Query( $query_args );

				$current_date = $prev_post->posts[0]->post_date;
			} else {
				$current_date = date( 'Y-m-d H:i:s' );
			}

			while ( have_posts() ) : the_post();
				$prev_date = $post->post_date;

				$query_args = [
					'category__in' => [ $moblog ],
					'date_query' => [
						'before' => $current_date,
						'after' => $prev_date,
					],
					'posts_per_page' => 500,
					'no_found_rows' => true,
				];

				$images = new WP_Query( $query_args );
				if ( $images->have_posts() ) :
			?>
				<div class="image-grid">
				<?php while( $images->have_posts() ) : $images->the_post(); ?>
					<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'thumbnail' ); ?></a>
				<?php endwhile; ?>
	<span class="clear"></span>
				</div>
			<?php endif; ?>

					<?php
						wp_reset_postdata();

						/* Include the Post-Format-specific template for the content.
						* If you want to overload this in a child theme then include a file
						* called content-___.php (where ___ is the Post Format name) and that will be used instead.
						*/
						get_template_part( 'content', get_post_format() );

						$current_date = $prev_date;
					?>

			<?php endwhile; ?>

			<?php ryu_content_nav( 'nav-below' ); ?>

		<?php else : ?>

			<?php get_template_part( 'no-results', 'index' ); ?>

		<?php endif; ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>
