<?php
/**
 * The Template for displaying all single posts.
 *
 * @package DJB_RRR
 */

get_header(); ?>

		<div id="container">
			<div id="content" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

        <h1 class="entry-title"><?php the_title(); ?></h1>
			
        <?php echo do_shortcode('[route_summary]') ?>

        <?php the_content(); ?>

        <?php echo do_shortcode('[route_details]') ?>

		<?php endwhile; // end of the loop. ?>

		</div><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>