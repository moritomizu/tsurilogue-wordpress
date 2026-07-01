<?php
/**
 * TSURILOGUE Next.js revalidation config example.
 *
 * Copy this file on the production server as:
 *
 * wp-content/mu-plugins/tsurilogue-revalidate-config.php
 *
 * Do not commit the copied file because it contains the Next.js revalidation
 * secret. The production filename is ignored by .gitignore.
 */

defined( 'ABSPATH' ) || exit;

add_filter(
	'tsurilogue_seo_tools_revalidate_endpoint',
	function () {
		return 'https://www.tsurilogue.com/api/revalidate';
	}
);

add_filter(
	'tsurilogue_seo_tools_revalidate_secret',
	function () {
		return 'REPLACE_WITH_NEXTJS_REVALIDATE_SECRET';
	}
);
