# TSURILOGUE-MEDIA WordPress

TSURILOGUE-MEDIA WordPress is the customization repository for the TSURILOGUE SEO media site.

This repository is used to manage the WordPress customization layer for SEO acquisition, canonical control, article CTA insertion, structured data support, future AI-related article navigation, and JIN child theme customization.

## Project Overview

TSURILOGUE-MEDIA is the media section of TSURILOGUE.

The goal is to operate WordPress as an SEO acquisition engine while keeping all project-specific code isolated from WordPress core, the JIN parent theme, uploads, and third-party plugin source files.

Primary responsibilities:

- Canonical URL automation from the WordPress origin to the public media URL.
- Article-bottom CTA insertion for TSURILOGUE.
- Structured data support for Article, Breadcrumb, and Organization.
- Foundation for AI-related article navigation.
- JIN child theme customization.
- Safe integration with SEO SIMPLE PACK.

## URLs

Public URL:

```text
https://tsurilogue.com/media
```

WordPress origin URL:

```text
https://tsurilogue.tapiyota.com
```

Canonical URLs must use the public URL, not the WordPress origin URL.

## Editable Scope

Only the following paths may be edited for project features:

```text
wp-content/plugins/tsurilogue-seo-tools/
wp-content/themes/jin-child/
```

## Forbidden Scope

Do not edit:

```text
WordPress core
wp-content/themes/jin/
wp-content/uploads/
wp-config.php
wp-content/plugins/seo-simple-pack/
```

Notes:

- The JIN parent theme must remain update-safe.
- WordPress core must never be customized directly.
- Uploads are content assets and must not be used for code changes.
- SEO SIMPLE PACK must be extended through hooks and filters only.

## Theme

Parent theme:

```text
JIN
wp-content/themes/jin/
```

Child theme:

```text
jin-child
wp-content/themes/jin-child/
```

All visual customizations for TSURILOGUE-MEDIA should be implemented in the child theme.

## Plugins

SEO plugin:

```text
SEO SIMPLE PACK
wp-content/plugins/seo-simple-pack/
```

Project plugin:

```text
TSURILOGUE SEO Tools
wp-content/plugins/tsurilogue-seo-tools/
```

`TSURILOGUE SEO Tools` manages TSURILOGUE-MEDIA-specific SEO support features.

After deployment, activate this plugin from the WordPress admin plugin screen.

## Phase Policy

Development should proceed in small, safe phases.

Do not perform a large rewrite at once. Keep each change scoped, reviewable, and reversible.

Current phase foundation:

- README and operating rules.
- Dedicated project plugin.
- Canonical URL conversion.
- Article-bottom CTA insertion.
- Structured data support foundation.
- Future AI-related article navigation policy.
- JIN child theme CSS foundation.

## Canonical Policy

Canonical output must be converted from the WordPress origin URL to the public media URL.

Origin:

```text
https://tsurilogue.tapiyota.com
```

Public:

```text
https://tsurilogue.com/media
```

Examples:

```text
https://tsurilogue.tapiyota.com/
=> https://tsurilogue.com/media/

https://tsurilogue.tapiyota.com/sample-post/
=> https://tsurilogue.com/media/sample-post/
```

Target page types:

- Front page
- Posts
- Pages
- Categories
- Tags
- Archives

Implementation policy:

- Use WordPress hooks and SEO SIMPLE PACK filters.
- Do not edit SEO SIMPLE PACK source files.
- Keep canonical output consistent with noindex settings.
- If a page is intentionally noindexed by SEO SIMPLE PACK, do not force indexability from this plugin.

Current implementation:

- `ssp_output_canonical` converts SEO SIMPLE PACK canonical URLs.
- `ssp_output_og_url` converts SEO SIMPLE PACK OGP URLs so OGP does not point to the origin.
- `get_canonical_url` converts WordPress standard canonical URLs if they are used.
- JIN custom canonical meta value `jin_canonical` is converted on frontend output when present.

## CTA Policy

All post pages should be able to show a TSURILOGUE CTA at the end of the article body.

CTA text:

```text
今日の釣果、未来につながります。

TSURILOGUEなら、潮位・風向・水温・釣果写真・タックル情報をまとめて記録できます。記録を重ねるほど、自分だけの釣れる条件が見えてきます。

無料でTSURILOGUEをはじめる
```

CTA link:

```text
https://tsurilogue.com
```

Display conditions:

- Show only on single post pages.
- Do not show in the WordPress admin.
- Do not auto-insert into fixed pages.
- Keep the ON/OFF decision separated for future settings UI.

Current implementation:

- `the_content` filter appends CTA HTML only when `is_singular( 'post' )`, inside the main loop, and on the main query.
- The CTA can be disabled by the `tsurilogue_seo_tools_enable_article_cta` filter.
- CTA styles are managed in the JIN child theme.

## Structured Data Policy

Structured data support should cover:

- Article
- Breadcrumb
- Organization

Important:

- Do not duplicate structured data already output by JIN or SEO SIMPLE PACK.
- Confirm current source output before enabling any JSON-LD output.
- The first implementation should provide a minimal foundation and keep output disabled by default unless explicitly enabled.

Current implementation:

- `TSURILOGUE SEO Tools` includes a structured data foundation.
- JSON-LD output is disabled by default.
- It can be enabled with the `tsurilogue_seo_tools_enable_structured_data` filter after confirming there is no duplicate output.

Current output check method:

1. Open a target page.
2. View page source.
3. Search for `application/ld+json`.
4. Search for `Article`, `BreadcrumbList`, and `Organization`.
5. Confirm whether JIN or SEO SIMPLE PACK already outputs equivalent JSON-LD.
6. Enable TSURILOGUE supplemental output only if there is no duplicate.

## AI Related Article Navigation Policy

Full AI recommendation is not implemented in the current phase.

Future related article navigation should be designed in stages:

- Same-category related articles.
- Same-tag related articles.
- Manual editorial recommendations.
- Future AI recommendations based on content, taxonomy, and user intent.
- Connection to Premium navigation where relevant.

Implementation policy:

- Start with deterministic WordPress queries.
- Avoid heavy processing during page rendering.
- Keep AI recommendation output replaceable.
- Separate recommendation data generation from frontend display.

## JIN Child Theme Customization Policy

The JIN parent theme must not be edited.

The child theme provides the customization foundation for:

- TSURILOGUE colors.
- CTA CSS.
- Button design.
- Article-bottom CTA design.

Current CSS foundation:

- CSS custom properties for TSURILOGUE colors.
- `.tsurilogue-cta` component styles.
- `.tsurilogue-button` base button styles.
- Responsive article CTA layout.

## Directory Structure

```text
.
├── README.md
└── wp-content/
    ├── languages/
    ├── mu-plugins/
    │   └── automation-by-installatron.php
    ├── plugins/
    │   ├── akismet/
    │   ├── hello.php
    │   ├── seo-simple-pack/
    │   └── tsurilogue-seo-tools/
    │       └── tsurilogue-seo-tools.php
    ├── themes/
    │   ├── jin/
    │   ├── jin-child/
    │   │   ├── functions.php
    │   │   ├── screenshot.png
    │   │   ├── style.css
    │   │   └── style.scss
    │   └── twentytwentyfive/
    └── uploads/
```

## Git Workflow

Rules:

- Keep changes small and phase-based.
- Do not mix unrelated changes in one commit.
- Do not commit generated local files such as `.DS_Store`.
- Review diffs before committing.
- Do not revert user changes unless explicitly requested.
- Use clear branch names such as `feature/canonical-automation` or `feature/article-cta`.

## Codex Operating Rules

Codex must follow these rules:

- This chat/project handles WordPress only.
- Read existing files before editing.
- Edit only `wp-content/plugins/tsurilogue-seo-tools/` and `wp-content/themes/jin-child/` for feature code.
- README updates are allowed.
- Do not edit WordPress core.
- Do not edit the JIN parent theme.
- Do not edit uploads.
- Do not edit `wp-config.php`.
- Do not edit SEO SIMPLE PACK source files.
- Preserve existing user changes in the working tree.
- Run syntax checks after PHP changes when possible.
- Document verification steps in README.

## Future Expansion

Planned future work:

- Admin setting for enabling/disabling article CTA.
- Admin setting for CTA text and link.
- Canonical diagnostics screen.
- Structured data duplicate detection.
- Article schema enrichment.
- Breadcrumb schema enrichment.
- Organization schema configuration.
- Same-category related article block.
- Same-tag related article block.
- AI-related article recommendation foundation.
- Premium navigation and conversion tracking.
- Conditional asset loading.
- Frontend performance improvements.

## Verification Steps

After deployment, verify the following:

Precheck:

- Confirm `TSURILOGUE SEO Tools` is activated in the WordPress admin plugin screen.

1. Open `https://tsurilogue.com/media`.
2. Open a post page.
3. View page source and confirm the canonical URL starts with `https://tsurilogue.com/media/`.
4. Confirm the article-bottom CTA is displayed on post pages.
5. Confirm the WordPress admin dashboard works normally.
6. Confirm SEO SIMPLE PACK OGP settings are not broken.

Additional checks:

- Confirm fixed pages do not receive the automatic article CTA.
- Confirm admin screens do not show frontend CTA output.
- Confirm no canonical URL points to `https://tsurilogue.tapiyota.com`.
- Confirm no duplicate JSON-LD is output before enabling TSURILOGUE structured data.
