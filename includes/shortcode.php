<?php
/**
 * Shortcode: [product_image_carousel category="slug1,slug2" limit="10"]
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Splide product carousel shortcode
 */
function wpc_product_carousel_shortcode( $atts ) {
	ob_start();

	// Default attributes
	$atts = shortcode_atts(
		array(
			'category' => '', // Comma-separated slugs
			'limit'    => 10,
		),
		$atts,
		'product_image_carousel'
	);

	// Prepare category array
	$categories = array_map( 'sanitize_text_field', explode( ',', $atts['category'] ) );

	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => intval( $atts['limit'] ),
		'post_status'    => 'publish',
	);

	// If categories provided, add tax query
	if ( ! empty( $categories ) && $categories[0] !== '' ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $categories,
				'operator' => 'IN',
			),
		);
	}

	$loop = new WP_Query( $args );

	if ( $loop->have_posts() ) :
		?>
		<section class="splide splide-normal" aria-label="Splide Product Carousel">
			<div class="splide__track">
				<ul class="splide__list">
					<?php
					while ( $loop->have_posts() ) :
						$loop->the_post();
						global $product;

						$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
						$title = get_the_title();
						$link  = get_permalink();
						$price = $product ? $product->get_price_html() : '';

						?>
						<li class="splide__slide">
							<a href="<?php echo esc_url( $link ); ?>">
								<?php if ( ! empty( $image[0] ) ) : ?>
									<img src="<?php echo esc_url( $image[0] ); ?>" class="img-fluid" alt="<?php echo esc_attr( $title ); ?>">
								<?php endif; ?>
								<h3 class="product-title"><?php echo esc_html( $title ); ?></h3>
								<?php if ( $price ) : ?>
									<span class="price"><?php echo wp_kses_post( $price ); ?></span>
								<?php endif; ?>
							</a>
						</li>
						<?php
					endwhile;
					?>
				</ul>
			</div>
		</section>
		<?php
	endif;

	wp_reset_postdata();

	return ob_get_clean();
}
add_shortcode( 'product_image_carousel', 'wpc_product_carousel_shortcode' );
