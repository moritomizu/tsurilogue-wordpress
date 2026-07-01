<?php

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'jin-child-style', get_stylesheet_uri(), [ 'parent-style' ], filemtime( get_stylesheet_directory() . '/style.css' ) );
}

add_action( 'wp_footer', 'tsurilogue_media_service_navigation', 5 );
function tsurilogue_media_get_default_service_chrome() {
	$base_url = 'https://www.tsurilogue.com/ja';

	return [
		'base_url'     => $base_url,
		'logo_url'     => 'https://www.tsurilogue.com/icons/trlg-logo.png',
		'header_links' => [
			[
				'url'   => $base_url,
				'label' => 'サービスTOP',
			],
			[
				'url'   => $base_url . '/media',
				'label' => 'メディア',
			],
			[
				'url'   => 'https://www.tsurilogue.com/how-to-use-app',
				'label' => 'アプリのように使う方法',
			],
			[
				'url'   => 'https://www.tsurilogue.com/feedback',
				'label' => 'ご意見・ご感想',
			],
			[
				'url'   => 'https://www.tsurilogue.com/login',
				'label' => 'ログイン',
			],
		],
		'footer_links' => [
			[
				'url'   => 'https://www.tsurilogue.com/how-to-use-app',
				'label' => 'アプリのように使う方法',
			],
			[
				'url'   => 'https://www.tsurilogue.com/feedback',
				'label' => 'ご意見・ご感想',
			],
			[
				'url'   => 'https://www.tsurilogue.com/terms',
				'label' => '利用規約',
			],
			[
				'url'   => 'https://www.tsurilogue.com/privacy',
				'label' => 'プライバシーポリシー',
			],
			[
				'url'   => 'https://www.tsurilogue.com/login',
				'label' => 'お問い合わせ',
			],
		],
		'tab_items'    => [
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
		],
	];
}

function tsurilogue_media_get_remote_service_chrome() {
	$config_url = apply_filters( 'tsurilogue_media_service_chrome_config_url', '' );

	if ( ! $config_url ) {
		return [];
	}

	$cache_key = 'tsurilogue_media_service_chrome';
	$cached    = get_transient( $cache_key );

	if ( false !== $cached ) {
		return is_array( $cached ) ? $cached : [];
	}

	$response = wp_remote_get(
		$config_url,
		[
			'timeout' => 2,
		]
	);

	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		set_transient( $cache_key, [], 30 * MINUTE_IN_SECONDS );
		return [];
	}

	$data = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( ! is_array( $data ) ) {
		set_transient( $cache_key, [], 30 * MINUTE_IN_SECONDS );
		return [];
	}

	set_transient( $cache_key, $data, HOUR_IN_SECONDS );

	return $data;
}

function tsurilogue_media_get_service_chrome() {
	$default = tsurilogue_media_get_default_service_chrome();
	$remote  = tsurilogue_media_get_remote_service_chrome();

	if ( empty( $remote ) ) {
		return $default;
	}

	foreach ( [ 'base_url', 'logo_url' ] as $key ) {
		if ( empty( $remote[ $key ] ) || ! is_string( $remote[ $key ] ) ) {
			unset( $remote[ $key ] );
		}
	}

	foreach ( [ 'header_links', 'footer_links' ] as $key ) {
		if ( empty( $remote[ $key ] ) || ! is_array( $remote[ $key ] ) ) {
			unset( $remote[ $key ] );
			continue;
		}

		$remote[ $key ] = tsurilogue_media_normalize_service_links( $remote[ $key ], $default[ $key ] );
	}

	if ( empty( $remote['tab_items'] ) || ! is_array( $remote['tab_items'] ) ) {
		unset( $remote['tab_items'] );
	} else {
		$remote['tab_items'] = tsurilogue_media_normalize_service_links( $remote['tab_items'], $default['tab_items'], true );
	}

	return array_merge( $default, $remote );
}

function tsurilogue_media_normalize_service_links( $links, $fallback, $require_icon = false ) {
	$normalized = [];

	foreach ( $links as $link ) {
		if ( empty( $link['url'] ) || empty( $link['label'] ) ) {
			continue;
		}

		$item = [
			'url'   => (string) $link['url'],
			'label' => (string) $link['label'],
		];

		if ( $require_icon ) {
			if ( empty( $link['icon'] ) ) {
				continue;
			}

			$item['icon'] = (string) $link['icon'];
		}

		$normalized[] = $item;
	}

	return $normalized ? $normalized : $fallback;
}

function tsurilogue_media_service_navigation() {
	if ( is_admin() ) {
		return;
	}

	$chrome       = tsurilogue_media_get_service_chrome();
	$base_url     = $chrome['base_url'];
	$logo_url     = $chrome['logo_url'];
	$items        = $chrome['tab_items'];
	$header_links = $chrome['header_links'];
	$footer_links = $chrome['footer_links'];
	?>
	<header class="tsurilogue-service-header" role="banner">
		<a class="tsurilogue-service-header__brand" href="<?php echo esc_url( $base_url ); ?>" aria-label="TSURILOGUE">
			<img src="<?php echo esc_url( $logo_url ); ?>" alt="TSURILOGUE">
		</a>
		<nav class="tsurilogue-service-header__nav" aria-label="TSURILOGUE navigation">
			<?php foreach ( $header_links as $link ) : ?>
				<a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a>
			<?php endforeach; ?>
		</nav>
	</header>
	<footer class="tsurilogue-service-footer" role="contentinfo">
		<div class="tsurilogue-service-footer__inner">
			<a class="tsurilogue-service-footer__brand" href="<?php echo esc_url( $base_url ); ?>" aria-label="TSURILOGUE">
				<img src="<?php echo esc_url( $logo_url ); ?>" alt="TSURILOGUE">
			</a>
			<nav class="tsurilogue-service-footer__nav" aria-label="TSURILOGUE footer navigation">
				<?php foreach ( $footer_links as $link ) : ?>
					<a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a>
				<?php endforeach; ?>
			</nav>
			<p class="tsurilogue-service-footer__credit">created by TaPiYoTa</p>
		</div>
	</footer>
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
