<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

	<div id="primary" class="grid_12">
		<div id="content" role="main">

		<?php

		 $args = array(
   			'post_type' => 'articles',
   			'meta_key' => 'sp_sort_date_num',
   			'orderby' => 'meta_value_num',
   			'order' => 'DESC',
   			'posts_per_page' => 10,
 		);
 		$loop = new WP_Query($args);

		while ( $loop->have_posts() ) : $loop->the_post(); ?>
			<!-- get the post data -->
			<?php
			$date_published = get_post_meta(get_the_ID(), 'sp_date_published', true);
			$publication = get_post_meta(get_the_id(), 'sp_publication', true); ?>
			<article>
			<h2 class="entry-title">
				<a href='<?php the_permalink(); ?>'><?php the_title(); ?></a>
			</h2>
			<?php 
			if (strlen($date_published)>0) { ?>
				<h5 class="article-info">First published <?php echo $date_published; ?> in <?php echo $publication; ?>.</h5>
				<?php } 
			else { ?>
				<h5 class="article-info"></h5>
			<?php } ?>
			<div class="entry-content">
				<?php the_excerpt(); ?>
			</div>
			<div class='swirl'></div>
			</article>
		<?php endwhile; ?>

		<div class="frame pagination">
			<?php 
				$big = 999999999; // need an unlikely integer

				echo paginate_links( array(
					'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format' => '?paged=%#%',
					'current' => max( 1, get_query_var('paged') ),
					'total' => $loop->max_num_pages
				) ); ?>
		</div><!-- end navi frame -->

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>