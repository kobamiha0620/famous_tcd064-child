<?php
$dp_options = get_design_plus_option();

get_header();
?>
<main class="l-main">
<?php
get_template_part( 'template-parts/page-header' );
if ( $dp_options['show_breadcrumb_single_works'] ) :
	get_template_part( 'template-parts/breadcrumb' );
endif;

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
?>
	<article class="p-entry-works l-inner">
<?php
		$arr_gallery_html = array();
		$modal_html = '';

		foreach( (array) $post->repeater_gallery as $gallery ) :
			$gallery = array_merge(
				array(
					'media_type' => 'image',
					'title' => '',
					'desc' => '',
					'image' => '',
					'thumbnail_size' => 'type1',
					'video' => '',
					'youtube_url' => ''
				),
				$gallery
			);

			if ( ! empty( $gallery['title'] ) ) :
				$gallery['title'] = strip_tags( $gallery['title'] );
			endif;
			if ( ! empty( $gallery['desc'] ) ) :
				$gallery['desc'] = strip_tags( $gallery['desc'] );
			endif;

			$arr_url = array();
			if ( $gallery['image'] ) :
				$image = wp_get_attachment_image_src( $gallery['image'], 'full' );
				if ( $image ) :
					$arr_url['image'] = $image[0];
					if ( 'type2' === $gallery['thumbnail_size'] ) :
						$image = wp_get_attachment_image_src( $gallery['image'], 'works2' );
					elseif ( 'type3' === $gallery['thumbnail_size'] ) :
						$image = wp_get_attachment_image_src( $gallery['image'], 'works3' );
					else :
						$image = wp_get_attachment_image_src( $gallery['image'], 'works1' );
					endif;
					if ( $image ) :
						$arr_url['thumbnail']= $image[0];
					endif;
				endif;
				$image = null;
			endif;

			// video
			if ( 'video' === $gallery['media_type'] ) :
				if ( $gallery['video'] ) :
					$arr_url['video'] = wp_get_attachment_url( $gallery['video'] );
					if ( $arr_url['video'] ) :
						$arr_url['link'] = $arr_url['video'];
					endif;
				endif;

			// youtube
			elseif ( 'youtube' === $gallery['media_type'] ) :
				if ( $gallery['youtube_url'] ) :
					// youtubeのvideoidを取得
					// parse youtube video id
					// https://stackoverflow.com/questions/2936467/parse-youtube-video-id-using-preg-match
					if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[\w\-?&!#=,;]+/[\w\-?&!#=/,;]+/|(?:v|e(?:mbed)?)/|[\w\-?&!#=,;]*[?&]v=)|youtu\.be/)([\w-]{11})(?:[^\w-]|\Z)%i', $gallery['youtube_url'], $matches ) ) :
						$arr_url['link'] = 'https://www.youtube.com/watch?v=' . esc_attr( $matches[1] );
						$arr_url['youtube_video_id'] = $matches[1];
						if ( empty( $arr_url['thumbnail'] ) ) :
							$arr_url['thumbnail'] = 'https://i.ytimg.com/vi/' . esc_attr( $matches[1] ) . '/sddefault.jpg';
						endif;
					else :
						$arr_url['embed'] = wp_oembed_get( $gallery['youtube_url'] );
						if ( $arr_url['embed'] ) :
							$arr_url['link'] = $gallery['youtube_url'];
						endif;
					endif;
				endif;

			// image
			elseif ( ! empty( $arr_url['image'] ) ) :
				$arr_url['link'] = $arr_url['image'];
			endif;

			if ( empty( $arr_url['link'] ) ) :
				continue;
			endif;

			if ( empty( $arr_url['thumbnail'] ) ) :
				$arr_url['thumbnail'] = get_template_directory_uri() . '/img/no-image-600x600.gif';
			endif;

			$item_class = 'p-works-gallery__item p-works-gallery__item--' . esc_attr( $gallery['thumbnail_size'] );
			if ( ! empty( $gallery['title'] ) ) :
				$item_class .= ' p-works-gallery__item--has-caption';
			endif;

			$item_attr = '';
			if ( ! empty( $gallery['title'] ) ) :
				$item_attr .= ' data-title="' . esc_attr( $gallery['title'] ). '"';
			endif;
			if ( ! empty( $gallery['desc'] ) ) :
				$item_attr .= ' data-desc="' . esc_attr( $gallery['desc'] ). '"';
			endif;
			if ( ! empty( $arr_url['video'] ) ) :
				$item_class .= ' p-works-gallery__item--video';
				$item_attr .= ' data-modal-type="video"';
			elseif ( ! empty( $arr_url['youtube_video_id'] ) ) :
				$item_class .= ' p-works-gallery__item--youtube';
				$item_attr .= ' data-modal-type="youtube"';
				$item_attr .= ' data-modal-youtube="' . esc_attr( $arr_url['youtube_video_id'] ). '"';
			elseif ( ! empty( $arr_url['embed'] ) ) :
				$item_attr .= ' data-modal-type="embed"';
				$item_attr .= ' data-modal-embed="' . esc_attr( $arr_url['embed'] ). '"';
			endif;

			$gallery_item_html = '<div class="' . $item_class . '"' . $item_attr . '>';
			$gallery_item_html .= '<a class="p-hover-effect--' . esc_attr( $dp_options['hover_type'] ) . '" href="' . esc_attr( $arr_url['link'] ) . '" target="_blank">';
			$gallery_item_html .= '<div class="p-works-gallery__thumbnail p-hover-effect__image js-object-fit-cover">';
			$gallery_item_html .= '<img src="' . esc_attr( $arr_url['thumbnail'] ) . '" alt="' . esc_attr( $gallery['title'] ) . '">';
			$gallery_item_html .= '</div>';

			if ( ! empty( $gallery['title'] ) ) :
				$gallery_item_html .= '<h3 class="p-works-gallery__caption">' . esc_html( $gallery['title'] ) . '</h3>';
			endif;

			$gallery_item_html .= '</a>';
			$gallery_item_html .= '</div>';

			$arr_gallery_html[] = $gallery_item_html;
		endforeach;

		if ( $arr_gallery_html ) :
			echo "\t\t" . '<div class="p-works-gallery p-entry-works__gallery">' . "\n";
			echo "\t\t\t" . implode( "\n\t\t\t", $arr_gallery_html ) . "\n";
			echo "\t\t</div>\n";
			$arr_gallery_html = null;
		endif;
?>
		<div class="p-entry-works__contents">
			<div class="p-entry-works__contents__inner">
				<h1 class="p-entry__title p-entry-works__title"><?php the_title(); ?></h1>
<?php

		if ( $dp_options['show_sns_top_works'] ) :
			get_template_part( 'template-parts/sns-btn-top' );
		endif;
?>
				<div class="p-entry__body p-entry-works__body">
<?php
		the_content();

		if ( ! post_password_required() ) :
			wp_link_pages( array(
				'before' => '<div class="p-page-links">',
				'after' => '</div>',
				'link_before' => '<span>',
				'link_after' => '</span>'
			) );
		endif;

		$notes_html = '';

		if ( $dp_options['show_category_works'] && $dp_options['use_works_category'] ) :
			$categories = get_the_terms( $post->ID, $dp_options['works_category_slug'] );
			if ( $categories && ! is_wp_error( $categories ) ) :
				$row_desc = '';
				foreach ( $categories as $key => $category ) :
					if ( 0 !== $key ) :
						$row_desc .= ', ';
					endif;
					$row_desc .= '<a href="' . esc_attr( get_term_link( $category ) ) . '">' . esc_html( $category->name ) . '</a>';
				endforeach;
				if ( $row_desc ) :
					$notes_html .= '<dt><p>CATEGORIES</p></dt>';
					$notes_html .= '<dd><p>' . $row_desc . '</p></dd>';
				endif;
			endif;
		endif;

		$notes = $post->notes;
		if ( isset( $notes['headline'][0] ) ) :
			foreach( array_keys( $notes['headline'] ) as $repeater_index ) :
				if ( isset( $notes['headline'][$repeater_index] ) ) :
					$row_headline = $notes['headline'][$repeater_index];
				else :
					$row_headline = '';
				endif;
				if ( isset( $notes['desc'][$repeater_index] ) ) :
					$row_desc = $notes['desc'][$repeater_index];
					// URL自動リンク
					if ( strpos( $row_desc, 'http' ) !== false ) :
						$pattern = '/(=[\"\'])?https?:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+/';
						$row_desc = preg_replace_callback( $pattern, function( $matches ) {
							// 既にリンク等の場合はそのまま
							if ( isset( $matches[1] ) ) return $matches[0];
							return "<a href=\"{$matches[0]}\" target=\"_blank\">{$matches[0]}</a>";
						}, $row_desc );
					endif;
				else :
					$row_desc = '';
				endif;
				if ( $row_headline ) :
					$notes_html .= '<dt>' . str_replace( array( "\r\n", "\r", "\n" ), '', wpautop( $row_headline ) ) . '</dt>';
				endif;
				if ( $row_desc ) :
					$notes_html .= '<dd>' . str_replace( array( "\r\n", "\r", "\n" ), '', wpautop( $row_desc ) ) . '</dd>';
				endif;
			endforeach;

		endif;

		if ( $notes_html ) :
?>
					<dl class="p-entry-works__notes"><?php echo $notes_html; ?></dl>
<?php
		endif;
?>
				</div>
<?php
		$sns_html = '';
		if ( $post->facebook_url ) :
			$sns_html .= '<li class="p-social-nav__item p-social-nav__item--facebook"><a href="' . esc_attr( $post->facebook_url ) . '" target="_blank"></a></li>';
		endif;
		if ( $post->twitter_url ) :
			$sns_html .= '<li class="p-social-nav__item p-social-nav__item--twitter"><a href="' . esc_attr( $post->twitter_url ) . '" target="_blank"></a></li>';
		endif;
		if ( $post->tiktok_url ) :
			$sns_html .= '<li class="p-social-nav__item p-social-nav__item--tiktok"><a href="' . esc_attr( $post->tiktok_url ) . '" target="_blank"></a></li>';
		endif;
		if ( $post->instagram_url ) :
			$sns_html .= '<li class="p-social-nav__item p-social-nav__item--instagram"><a href="' . esc_attr( $post->instagram_url ) . '" target="_blank"></a></li>';
		endif;
		if ( $post->pinterest_url ) :
			$sns_html .= '<li class="p-social-nav__item p-social-nav__item--pinterest"><a href="' . esc_attr( $post->pinterest_url ) . '" target="_blank"></a></li>';
		endif;
		if ( $post->youtube_url ) :
			$sns_html .= '<li class="p-social-nav__item p-social-nav__item--youtube"><a href="' . esc_attr( $post->youtube_url ) . '" target="_blank"></a></li>';
		endif;
		if ( $post->contact_url ) :
			$sns_html .= '<li class="p-social-nav__item p-social-nav__item--contact"><a href="' . esc_attr( $post->contact_url ) . '" target="_blank"></a></li>';
		endif;
		if ( $post->rss_url ) :
			$sns_html .= '<li class="p-social-nav__item p-social-nav__item--rss"><a href="' . esc_attr( $post->rss_url ) . '" target="_blank"></a></li>';
		endif;
		if ( $sns_html ) :
?>
				<ul class="p-social-nav"><?php echo $sns_html; ?></ul>
<?php
		endif;

		if ( $dp_options['show_sns_btm_works'] ) :
			get_template_part( 'template-parts/sns-btn-btm' );
		endif;

		if ( $dp_options['show_next_post_works'] ) :
			$previous_post = get_previous_post();
			$next_post = get_next_post();
?>


				<ul class="p-pager p-entry-works__pager">
<?php
			if ( $previous_post ) :
?>
					<li class="p-pager__item p-pager__item--prev"><a href="<?php echo esc_url( get_permalink( $previous_post->ID ) ); ?>" title="<?php _e( 'Previous post', 'tcd-w' ); ?>">&#xe90f;</a></li>
<?php
			endif;
?>
					<li class="p-pager__item p-pager__item--index"><a href="<?php echo esc_url( get_post_type_archive_link( $dp_options['works_slug'] ) ); ?>" title="<?php echo esc_attr( $dp_options['works_breadcrumb_label'] ); ?>">&#xe5c4;</a></li>
<?php
			if ( $next_post ) :
?>
					<li class="p-pager__item p-pager__item--next"><a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" title="<?php _e( 'Next post', 'tcd-w' ); ?>">&#xe910;</a></li>
<?php
			endif;
?>
				</ul>
<?php
		endif;
?>
			<!-- 追記 -->
			<div class="p-cb__item-button__wrapper">
		<a class="p-cb__item-button p-button" href="https://chatmonster.jp/#cb_6" style="background-color: #333333;">お仕事の依頼はこちら</a>
	</div>
	<!-- 追記 -->


			</div>

		</div>
		
	</article>
<?php
	endwhile;
endif;
?>
</main>
<?php get_footer(); ?>


