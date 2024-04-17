<?php
$dp_options = get_design_plus_option();

if ( is_singular( $dp_options['information_slug'] ) ) :
	get_template_part( 'single-information' );
	return;
elseif ( is_singular( $dp_options['works_slug'] ) ) :
	get_template_part( 'single-works' );
	return;
endif;

$active_sidebar = get_active_sidebar();
get_header();
?>
<main class="l-main">
<?php
get_template_part( 'template-parts/page-header' );
if ( $dp_options['show_breadcrumb_single'] ) :
	get_template_part( 'template-parts/breadcrumb' );
endif;

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		$catlist_float = array();
		if ( has_category() ) :
			$categories = get_the_category();
			if ( $categories && ! is_wp_error( $categories ) ) :
				foreach( $categories as $category ) :
					$catlist_float[] = '<a class="p-category-item" href="' . get_category_link( $category ) . '">' . esc_html( $category->name ) . '</a>';
					break;
				endforeach;
			endif;
		endif;

		if ( $post->page_link && in_array( $post->page_link, array( 'type1', 'type2' ) ) ) :
			$page_link = $post->page_link;
		else :
			$page_link = $dp_options['page_link'];
		endif;

		if ( $active_sidebar ) :
?>
	<div class="l-inner l-2columns">
<?php
		endif;
?>
		<article class="p-entry <?php echo $active_sidebar ? 'l-primary' : 'l-inner'; ?>">
<?php
		if ( $dp_options['show_thumbnail'] && has_post_thumbnail() ) :
?>
			<div class="p-entry__thumbnail">
<?php
			echo "\t\t\t\t";
			the_post_thumbnail( 'size5' );
			echo "\n";

			if ( $catlist_float ) :
?>
	
<?php if(!in_category('menu')) : ?>
	<div class="p-float-category"><?php echo implode( '', $catlist_float ); ?></div>
<?php endif; ?>

<?php
			endif;
?>
			</div>
<?php
		elseif ( $catlist_float ) :
?>
			
			
			<?php if(!in_category('menu')) : ?>
				<div class="p-entry__category"><?php echo implode( '', $catlist_float ); ?></div>
<?php endif; ?>
<?php
		endif;

		if ( $dp_options['show_date'] ) :
?>
			<div class="p-entry__date_title">
				<time class="p-entry__date p-article__date" datetime="<?php the_time( 'c' ); ?>"><span class="p-article__date-day"><?php the_time( 'd' ); ?></span><span class="p-article__date-month"><?php echo mysql2date( 'M', $post->post_date, false ); ?></span><span class="p-article__date-year"><?php the_time( 'Y' ); ?></span></time>
				<h1 class="p-entry__title"><?php the_title(); ?></h1>
			</div>
<?php
		else :
?>
			<h1 class="p-entry__title"><?php the_title(); ?></h1>
<?php
		endif;
?>
			<div class="p-entry__inner">
<?php

		if ( $dp_options['show_sns_top'] ) :
			get_template_part( 'template-parts/sns-btn-top' );
		endif;
?>
				<div class="p-entry__body">
<?php
		the_content();

		if ( ! post_password_required() ) :
			if ( 'type2' === $page_link ):
				if ( $page < $numpages && preg_match( '/href="(.*?)"/', _wp_link_page( $page + 1 ), $matches ) ) :
?>
					<div class="p-entry__next-page">
						<a class="p-entry__next-page__link p-button" href="<?php echo esc_url( $matches[1] ); ?>"><?php _e( 'Read more', 'tcd-w' ); ?></a>
						<div class="p-entry__next-page__numbers"><?php echo $page . ' / ' . $numpages; ?></div>
					</div>
<?php
				endif;
			else:
				wp_link_pages( array(
					'before' => '<div class="p-page-links">',
					'after' => '</div>',
					'link_before' => '<span>',
					'link_after' => '</span>'
				) );
			endif;
		endif;
?>
				</div>
<?php
		if ( $dp_options['show_author'] ) :
			$author = get_user_by( 'id', $post->post_author );
			if ( $author->show_author ) :
				$sns_html = '';
				if ( $author->user_url ) :
					$sns_html .= '<li class="p-social-nav__item p-social-nav__item--url"><a href="' . esc_attr( $author->user_url ) . '" target="_blank"></a></li>';
				endif;
				if ( $author->facebook_url ) :
					$sns_html .= '<li class="p-social-nav__item p-social-nav__item--facebook"><a href="' . esc_attr( $author->facebook_url ) . '" target="_blank"></a></li>';
				endif;
				if ( $author->twitter_url ) :
					$sns_html .= '<li class="p-social-nav__item p-social-nav__item--twitter"><a href="' . esc_attr( $author->twitter_url ) . '" target="_blank"></a></li>';
				endif;
				if ( $author->tiktok_url ) :
					$sns_html .= '<li class="p-social-nav__item p-social-nav__item--tiktok"><a href="' . esc_attr( $author->tiktok_url ) . '" target="_blank"></a></li>';
				endif;
				if ( $author->instagram_url ) :
					$sns_html .= '<li class="p-social-nav__item p-social-nav__item--instagram"><a href="' . esc_attr( $author->instagram_url ) . '" target="_blank"></a></li>';
				endif;
				if ( $author->pinterest_url ) :
					$sns_html .= '<li class="p-social-nav__item p-social-nav__item--pinterest"><a href="' . esc_attr( $author->pinterest_url ) . '" target="_blank"></a></li>';
				endif;
				if ( $author->youtube_url ) :
					$sns_html .= '<li class="p-social-nav__item p-social-nav__item--youtube"><a href="' . esc_attr( $author->youtube_url ) . '" target="_blank"></a></li>';
				endif;
				if ( $author->contact_url ) :
					$sns_html .= '<li class="p-social-nav__item p-social-nav__item--contact"><a href="' . esc_attr( $author->contact_url ) . '" target="_blank"></a></li>';
				endif;
?>
				<div class="p-author__box">
					<div class="p-author__thumbnail">
						<a class="p-author__thumbnail__link p-hover-effect--<?php echo esc_attr( $dp_options['hover_type'] ); ?>" href="<?php echo get_author_posts_url( $author->ID ); ?>">
							<div class="p-hover-effect__image js-object-fit-cover"><?php echo get_avatar( $author->ID, 300 ); ?></div>
						</a>
					</div>
					<div class="p-author__info">
						<a class="p-author__link" href="<?php echo get_author_posts_url( $author->ID ); ?>"><?php echo _e( 'Author archive', 'tcd-w' ); ?></a>
						<h3 class="p-author__title"><?php echo esc_html( $author->display_name ); ?></h3>
						<p class="p-author__desc"><?php echo esc_html( mb_strimwidth( strip_tags( $author->description ), 0, 172, '...' ) ); ?></p>
<?php
					if ( $sns_html ) :
?>
						<ul class="p-social-nav"><?php echo $sns_html; ?></ul>
<?php
					endif;
?>
					</div>
				</div>
<?php
			endif;
		endif;

		if ( $dp_options['show_sns_btm'] ) :
			get_template_part( 'template-parts/sns-btn-btm' );
		endif;

		if ( has_category() || has_tag() || $dp_options['show_comment'] ) :
?>
<?php if(!in_category('menu')) : ?>

<ul class="p-entry__meta c-meta-box u-clearfix">
	<?php if ( has_category() ) : ?><li class="c-meta-box__item c-meta-box__item--category"><?php the_category( ', ' ); ?></li><?php endif; ?>
	<?php if ( has_tag() && get_the_tags() ) : ?><li class="c-meta-box__item c-meta-box__item--tag"><?php echo get_the_tag_list( '', ', ', '' ); ?></li><?php endif; ?>
	<?php if ( $dp_options['show_comment'] ) : ?><li class="c-meta-box__item c-meta-box__item--comment"><?php _e( 'Comments', 'tcd-w' ); ?>: <a href="#comment_headline"><?php echo get_comments_number( '0', '1', '%' ); ?></a></li><?php endif; ?>
</ul>
<?php endif; ?>

<?php
		endif;
?>
			</div>


<!-- 追記部分 --------------------------------------- -->
<?php if(in_category('pricemenu')) : ?>
	<div class="p-cb__item-button__wrapper">
		<a class="p-cb__item-button p-button" href="https://chatmonster.jp/#cb_6" style="background-color: #333;">お仕事の依頼はこちら</a>
	</div>
<?php endif; ?>

<!-- /追記部分 --------------------------------------- -->


<?php
		$previous_post = get_previous_post();
		$next_post = get_next_post();
		if ( $dp_options['show_next_post'] && ( $previous_post || $next_post ) ) :
?>
			<ul class="p-entry__nav c-entry-nav">
<?php
			if ( $previous_post ) :
?>
				<li class="c-entry-nav__item c-entry-nav__item--prev"><a href="<?php echo esc_url( get_permalink( $previous_post->ID ) ); ?>" data-prev="<?php _e( 'Previous post', 'tcd-w' ); ?>"><span class="u-hidden-sm"><?php echo esc_html( mb_strimwidth( strip_tags( $previous_post->post_title ), 0, 80, '...' ) ); ?></span></a></li>
<?php
			else :
?>
				<li class="c-entry-nav__item c-entry-nav__item--empty"></li>
<?php
			endif;
			if ( $next_post ) :
?>
				<li class="c-entry-nav__item c-entry-nav__item--next"><a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" data-next="<?php _e( 'Next post', 'tcd-w' ); ?>"><span class="u-hidden-sm"><?php echo esc_html( mb_strimwidth( strip_tags( $next_post->post_title ), 0, 80, '...' ) ); ?></span></a></li>
<?php
			else :
?>
				<li class="c-entry-nav__item c-entry-nav__item--empty"></li>
<?php
			endif;
?>
			</ul>
<?php
		endif;

		get_template_part( 'template-parts/advertisement' );

		if ( $dp_options['show_related_post'] ) :
			$args = array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'post__not_in' => array( $post->ID ),
				'posts_per_page' => $dp_options['related_post_num'],
				'orderby' => 'rand'
			);
			$categories = get_the_category();
			if ( $categories ) :
				$category_ids = array();
				foreach ( $categories as $category ) :
					if ( !empty( $category->term_id ) ) :
						$category_ids[] = $category->term_id;
					endif;
				endforeach;
				if ( $category_ids ) :
					$args['tax_query'][] = array(
						'taxonomy' => 'category',
						'field' => 'term_id',
						'terms' => $category_ids,
						'operator' => 'IN'
					);
				endif;
			endif;
			$the_query = new WP_Query( $args );
			if ( $the_query->have_posts() ) :
?>
			<section class="p-entry__related">
<?php
				if ( $dp_options['related_post_headline'] ) :
?>
				<h2 class="p-headline"><?php echo esc_html( $dp_options['related_post_headline'] ); ?></h2>
<?php
				endif;
?>
				<div class="p-entry__related-items">
<?php
				while ( $the_query->have_posts() ) :
					$the_query->the_post();
?>
					<article class="p-entry__related-item">
						<a class="p-hover-effect--<?php echo esc_attr( $dp_options['hover_type'] ); ?>" href="<?php the_permalink(); ?>">
							<div class="p-entry__related-item__thumbnail p-hover-effect__image js-object-fit-cover">
<?php
					echo "\t\t\t\t\t\t\t\t";
					if ( has_post_thumbnail() ) :
						the_post_thumbnail( 'size2' );
					else :
						echo '<img src="' . get_template_directory_uri() . '/img/no-image-300x300.gif" alt="">';
					endif;
					echo "\n";
?>
							</div>
							<h3 class="p-entry__related-item__title p-article__title"><?php echo mb_strimwidth( strip_tags( get_the_title() ), 0, is_mobile() ? 44 : 62, '...' ); ?></h3>
						</a>
					</article>
<?php
				endwhile;

				wp_reset_postdata();
?>
				</div>
			</section>
<?php
			endif;
		endif;

		if ( $dp_options['show_comment'] ) :
			comments_template( '', true );
		endif;
?>
		</article>
<?php
	endwhile;
endif;

if ( $active_sidebar ) :
	get_sidebar();
?>
	</div>
<?php
endif;
?>
</main>
<?php get_footer(); ?>