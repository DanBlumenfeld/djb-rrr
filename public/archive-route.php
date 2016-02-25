<?php
/**
 * The template for displaying Route archives.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package DJB_RRR
 */

get_header(); ?>

<div id="container">
			<div id="content" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php
						_e( 'Routes List', 'djb-rrr' );
					?>
				</h1>
				<?php
					// Show an optional term description.
					$term_description = term_description();
					if ( ! empty( $term_description ) ) :
						printf( '<div class="taxonomy-description">%s</div>', $term_description );
					endif;
				?>
			</header><!-- .page-header -->

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
            <h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
				<?php
					
                    //TODO: render route info , including LatLong and url info for use in map
                    echo do_shortcode('[route_summary]');
	                the_excerpt(); 

				?>
			<?php endwhile; ?>
            
		<?php else : ?>

			<?php get_template_part( 'no-results', 'archive' ); ?>

		<?php endif; ?>

			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
