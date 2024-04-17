<?php
global $post, $dp_options;
if ( ! $dp_options ) $dp_options = get_design_plus_option();

$signage = $catchphrase = $desc = $overlay = $overlay_opacity = null;

if ( is_404() ) :
	$signage = wp_get_attachment_url( $dp_options['image_404'] );
	$catchphrase = trim( $dp_options['catchphrase_404'] );
	$desc = trim( $dp_options['desc_404'] );
	$catchphrase_font_size = $dp_options['catchphrase_font_size_404'] ? $dp_options['catchphrase_font_size_404'] : 30;
	$desc_font_size = $dp_options['desc_font_size_404'] ? $dp_options['desc_font_size_404'] : 16;
	$color = $dp_options['color_404'] ? $dp_options['color_404'] : '#000000';
	$shadow1 = $dp_options['shadow1_404'] ? $dp_options['shadow1_404'] : 0;
	$shadow2 = $dp_options['shadow2_404'] ? $dp_options['shadow2_404'] : 0;
	$shadow3 = $dp_options['shadow3_404'] ? $dp_options['shadow3_404'] : 0;
	$shadow4 = $dp_options['shadow_color_404'] ? $dp_options['shadow_color_404'] : '#999999';
	$overlay = $dp_options['overlay_404'] ? $dp_options['overlay_404'] : '#000000';
	$overlay_opacity = floatval($dp_options['overlay_opacity_404'])>=0 ? floatval($dp_options['overlay_opacity_404']) : 0.5;

elseif ( is_page() ) :
	$signage = wp_get_attachment_url( $post->page_header_image );
	$catchphrase = trim( $post->page_headline ? $post->page_headline : $post->post_title );
	$catchphrase_font_size = $post->page_headline_font_size ? $post->page_headline_font_size : 30;
	$desc = trim( $post->page_desc );
	$desc_font_size = $post->page_desc_font_size ? $post->page_desc_font_size : 16;
	$color = $post->page_headline_color ? $post->page_headline_color : '#000000';
	$shadow1 = $post->page_headline_shadow1 ? $post->page_headline_shadow1 : 0;
	$shadow2 = $post->page_headline_shadow2 ? $post->page_headline_shadow2 : 0;
	$shadow3 = $post->page_headline_shadow3 ? $post->page_headline_shadow3 : 0;
	$shadow4 = $post->page_headline_shadow4 ? $post->page_headline_shadow4 : '#999999';
	$overlay = $post->page_overlay ? $post->page_overlay : '#000000';
	$overlay_opacity = floatval($post->page_overlay_opacity)>=0 ? floatval($post->page_overlay_opacity) : 0.5;

elseif ( is_post_type_archive( $dp_options['information_slug'] ) || is_singular( $dp_options['information_slug'] ) ) :
	$catchphrase = $dp_options['information_header_headline'] ? $dp_options['information_header_headline'] : $dp_options['information_breadcrumb_label'];
	$desc = $dp_options['information_header_desc'];

elseif ( is_tax( $dp_options['works_category_slug'] ) ) :
	$queried_object = get_queried_object();
	$catchphrase = $queried_object->name;
	$desc = $queried_object->description;

elseif ( is_post_type_archive( $dp_options['works_slug'] ) || is_singular( $dp_options['works_slug'] ) ) :
	$catchphrase = $dp_options['works_header_headline'] ? $dp_options['works_header_headline'] : $dp_options['works_breadcrumb_label'];
	$desc = $dp_options['works_header_desc'];

elseif ( is_post_type_archive( $dp_options['voice_slug'] ) || is_singular( $dp_options['voice_slug'] ) ) :
	$catchphrase = $dp_options['voice_header_headline'] ? $dp_options['voice_header_headline'] : $dp_options['voice_breadcrumb_label'];
	$desc = $dp_options['voice_header_desc'];

elseif ( is_author() ) :
	$author = get_queried_object();
	$catchphrase = sprintf( __( 'Archive for %s', 'tcd-w' ), esc_html( $author->display_name ) );

elseif ( is_search() ) :
	$catchphrase = sprintf( __( 'Search result for "%s"', 'tcd-w' ), esc_html( get_query_var( 's' ) ) );

else :
	$catchphrase = $dp_options['blog_header_headline'] ? $dp_options['blog_header_headline'] : $dp_options['blog_breadcrumb_label'];
	$desc = $dp_options['blog_header_desc'];
endif;

if ( $signage ) :
?>
	<header id="js-page-header" class="p-page-header__image"<?php if ( !empty( $signage ) ) echo ' style="background-image: url(' . esc_attr( $signage ) . ');"'; ?>>
		<div class="p-page-header__overlay" style="background-color: rgba(<?php echo esc_attr( implode( ', ', hex2rgb( $overlay ) ) ); ?>, <?php echo esc_attr( $overlay_opacity ); ?>);">
			<div class="p-page-header__inner l-inner" style="text-shadow: <?php echo esc_attr( $shadow1 ); ?>px <?php echo esc_attr( $shadow2 ); ?>px <?php echo esc_attr( $shadow3 ); ?>px <?php echo esc_attr( $shadow4 ); ?>;">
<?php
	if ( $catchphrase ) :
?>
				<h1 class="p-page-header__title" style="color: <?php echo esc_attr( $color ); ?>; font-size: <?php echo esc_attr( $catchphrase_font_size ); ?>px;"><?php echo esc_html( $catchphrase ); ?></h1>
<?php
	endif;
	if ( $desc ) :
?>
				<p class="p-page-header__desc" style="color: <?php echo esc_attr( $color ); ?>; font-size: <?php echo esc_attr( $desc_font_size ); ?>px;"><?php echo str_replace( array( "\r\n", "\r", "\n" ), '<br>', esc_html( $desc ) ); ?></p>
<?php
	endif;
?>
			</div>
		</div>
	</header>
<?php
elseif ( $catchphrase || $desc ) :
?>
	<header id="js-page-header" class="p-page-header">
		<div class="p-page-header__inner l-inner">
<?php
	if ( $catchphrase ) :
?>
			<h1 class="p-page-header__title"><?php echo esc_html( $catchphrase ); ?></h1>
<?php
	endif; ?>

<?php if(is_page('company')): ?>
    <p class="p-page-header__desc">会社概要</p>

<?php elseif(is_page('privacy-policy')): ?>
    <p class="p-page-header__desc">プライバシーポリシー</p>

<?php endif; ?>


<?php	if ( $desc ) :
?>
			<p class="p-page-header__desc"><?php echo str_replace( array( "\r\n", "\r", "\n" ), '<br>', esc_html( $desc ) ); ?></p>
<?php
	endif;
?>
		</div>
	</header>
<?php
endif;
