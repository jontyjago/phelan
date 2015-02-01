<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
<article>
	<header class='entry-header'>
		<?php if (is_single()) { ?>
			<h2 class='entry-title'><?php the_title(); ?></h2>
		<?php } else { ?>
			<h2 class='entry-title'><a href='<?php the_permalink();?>'><?php the_title(); ?></a></h2>
		<?php } ?>
		<?php
		$date_published = get_post_meta(get_the_ID(), 'sp_date_published', true);
		$publication = get_post_meta(get_the_id(), 'sp_publication', true);
		if (strlen($date_published)>0) { ?>
			<h5 class="article-info">First published <?php echo $date_published; ?> in <?php echo $publication; ?>.</h5>
		<?php } 
		else { ?>
			<h5 class="article-info"></h5>
		<?php } ?>
		<?php 
		if (is_single()) { ?>
			<div class='leaf'></div> 
		<?php } ?>
	</header>
	<div class='entry-content'>
		<?php 
		if (is_single()) { 
			the_content(); }
		else {
			the_excerpt();
		} ?>
	</div>
	<div class='swirl'></div>
</article>