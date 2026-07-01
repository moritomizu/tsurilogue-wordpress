<?php

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'jin-child-style', get_stylesheet_uri(), [ 'parent-style' ], wp_get_theme()->get( 'Version' ) );
}

add_action( 'wp_footer', 'tsurilogue_media_service_navigation', 5 );
function tsurilogue_media_service_navigation() {
	if ( is_admin() ) {
		return;
	}

	$base_url = 'https://www.tsurilogue.com/ja';
	$items    = [
		[
			'url'   => $base_url . '/catches',
			'label' => '一覧',
			'icon'  => '<path d="M8 6h12M8 12h12M8 18h12"></path><path d="M4 6h.01M4 12h.01M4 18h.01"></path>',
		],
		[
			'url'   => $base_url . '/ranking',
			'label' => '順位',
			'icon'  => '<path d="M8 4h8v4a4 4 0 0 1-8 0V4Z"></path><path d="M8 6H5a3 3 0 0 0 3 4M16 6h3a3 3 0 0 1-3 4M12 12v5M9 20h6M10 17h4"></path>',
		],
		[
			'url'   => $base_url . '/post',
			'label' => '投稿',
			'icon'  => '<path d="M12 5v14M5 12h14"></path>',
		],
		[
			'url'   => $base_url . '/map',
			'label' => 'マップ',
			'icon'  => '<path d="M12 21s6-5.2 6-11a6 6 0 0 0-12 0c0 5.8 6 11 6 11Z"></path><circle cx="12" cy="10" r="2"></circle>',
		],
		[
			'url'   => $base_url . '/analysis',
			'label' => '分析',
			'icon'  => '<path d="M4 19V5M4 19h16"></path><path d="M8 16v-5M12 16V8M16 16v-7"></path>',
		],
	];
	?>
	<header class="tsurilogue-service-header" role="banner">
		<a class="tsurilogue-service-header__brand" href="<?php echo esc_url( $base_url ); ?>" aria-label="TSURILOGUE">
			<img src="https://www.tsurilogue.com/icons/trlg-logo.png" alt="TSURILOGUE">
		</a>
		<nav class="tsurilogue-service-header__nav" aria-label="TSURILOGUE navigation">
			<a href="<?php echo esc_url( $base_url . '/media' ); ?>">メディア</a>
			<a href="<?php echo esc_url( $base_url . '/plans' ); ?>">プラン</a>
			<a href="<?php echo esc_url( $base_url . '/post' ); ?>">投稿</a>
		</nav>
	</header>
	<nav class="tsurilogue-service-tabbar" aria-label="TSURILOGUE app navigation">
		<div class="tsurilogue-service-tabbar__inner">
			<?php foreach ( $items as $item ) : ?>
				<a class="tsurilogue-service-tabbar__item" href="<?php echo esc_url( $item['url'] ); ?>">
					<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
						<?php echo $item['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</svg>
					<span><?php echo esc_html( $item['label'] ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</nav>
	<?php
}
