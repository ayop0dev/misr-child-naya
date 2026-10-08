<?php
/**
 * Naya homepage composition (replaces the Egstore theme's home.php).
 *
 * Sections, data and behaviour come from the Egstore theme and the commerce
 * plugin; this file only chooses them, in order, with this store's copy
 * (from the design in Egstore-theme/demo-home). Category cards use each
 * category's own thumbnail and link.
 *
 * The hero's figures and badges are the design's claims; the store confirms
 * them before going live (README.md). Sections of the design the theme cannot
 * render yet (flash deals countdown, routine finder, shop by concern, bundle
 * builder, clinical proof, ingredients, reviews, UGC, blog, FAQ, newsletter)
 * are added as the theme gains them.
 *
 * @package MisrChildNaya
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

$shopUrl = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/');
$categoryUrl = static function (string $slug) use ($shopUrl): string {
	$url = get_term_link($slug, 'product_cat');

	return is_string($url) ? $url : $shopUrl;
};

return [
	[
		'section' => 'hero',
		'config'  => [
			'layout'                 => 'split',
			'eyebrow'                => 'تركيبات سريرية طبيعية 100% لخلايا تتنفس',
			'eyebrow_style'          => 'pill',
			'title'                  => 'علم الطبيعة لجمال يدوم: روتينك الشامل',
			'title_highlight'        => 'للبشرة، الشعر والجسم',
			'description'            => 'نجمع بين أنقى الخلاصات النباتية العضوية وأحدث ابتكارات طب الجلدية السريرية. خالٍ تماماً من البارابين، العطور الاصطناعية، والسيليكون لتمنحي نفسك عناية نقية تستحقينها في مصر من نايا.',
			'mobile_align'           => 'center',
			// Shipped with the theme (assets/images/hero.webp, 960×1200, the
			// design's photo), so it is the same locally and on the store.
			'image'                  => get_stylesheet_directory_uri() . '/assets/images/hero.webp',
			'action_label'           => 'تسوقي كافة الأقسام',
			'action_url'             => $shopUrl,
			'secondary_action_label' => 'العروض',
			'secondary_action_url'   => $categoryUrl('offers'),
			'stats'                  => [
				['value' => '98%', 'label' => 'مكونات طبيعية ونقية'],
				['value' => '+65,000', 'label' => 'عميلة راضية في محافظات مصر'],
				['value' => '4.9 / 5', 'label' => 'تقييم موثق من أطباء الجلد'],
			],
			'badges'                 => [
				['icon' => 'zap', 'title' => 'نضارة زجاجية 72 ساعة', 'text' => 'حمض الهيالورونيك الثلاثي + النياسيناميد'],
				['icon' => 'shield-check', 'title' => 'معتمد سريرياً بمصر', 'text' => 'مختبر تحت إشراف أطباء الجلدية'],
			],
		],
	],
	[
		'section' => 'features',
		'config'  => [
			'id'     => 'pillars',
			'layout' => 'inline',
			'items'  => [
				['icon' => 'truck', 'title' => 'شحن سريع ومبرد', 'text' => 'القاهرة والجيزة والإسكندرية خلال 24-48 ساعة، وخلال 3 أيام لباقي المحافظات في عبوات عازلة للحرارة.'],
				['icon' => 'shield-check', 'title' => 'ضمان الرضا الذهبي 30 يوماً', 'text' => 'استرجاع أو استبدال فوري إن لم تلحظي نتيجة إيجابية في بشرتك أو شعرك.'],
				['icon' => 'message-circle', 'title' => 'استشارة جلدية مجانية', 'text' => 'تحدثي مباشرة عبر واتساب مع طبيبة جلدية معتمدة لبناء روتينك الأمثل وتحديد احتياجاتك.'],
				['icon' => 'banknote', 'title' => 'طرق دفع مصرية متنوعة', 'text' => 'فودافون كاش، إنستاباي، فوري، كروت ميزة، تقسيط ڤاليو بدون فوائد، أو الدفع نقداً عند الاستلام.'],
			],
		],
	],
	[
		'section' => 'category-showcase',
		'config'  => [
			'id'            => 'categories',
			'eyebrow'       => 'أقسام المتجر',
			'heading_align' => 'center',
			'title'         => 'استكشفي حسب عالم العناية',
			'description'   => 'تشكيلة شاملة مدروسة بعناية لتلبي جميع احتياجات روتينك اليومي من الرأس حتى أطراف القدمين.',
			'layout'        => 'grid',
			'card_style'    => 'circle',
			'show_count'    => true,
			'categories'    => [
				'العناية-بالوجه',
				'العناية-بالجسم',
				'العناية-بالشعر',
				'سكراب',
				'kits',
				'offers',
			],
		],
	],
	[
		'section' => 'product-collection',
		'config'  => [
			'id'            => 'deals',
			'eyebrow'       => 'عروض محدودة الكمية',
			'heading_align' => 'center',
			'title'         => 'تخفيضات الدفعة الحصرية: خصومات تصل إلى 40%',
			'description'   => 'دفعات طازجة تم إنتاجها حديثاً لضمان أقصى نشاط حيوي للمكونات. ينتهي العرض عند نفاد الكمية المخصصة.',
			'action_label'  => 'تسوقي العروض المتاحة',
			'action_url'    => $categoryUrl('offers'),
			'layout'        => 'carousel',
			'show_rating'   => true,
			'source'        => ['type' => 'on-sale', 'limit' => 8],
		],
	],
	[
		'section' => 'product-collection',
		'config'  => [
			'id'            => 'new-arrivals',
			'eyebrow'       => 'وصل حديثاً بالمخازن',
			'heading_align' => 'center',
			'title'         => 'أحدث ابتكارات العناية والجمال في السوق المصري من نايا',
			'description'   => 'تركيبات سريرية مطورة حديثاً تمتاز بأعلى تركيزات الفعالية لتمنحك نتائج ملموسة من أول أسبوعين.',
			'layout'        => 'carousel',
			'show_rating'   => true,
			'source'        => ['type' => 'latest', 'limit' => 8],
		],
	],
	[
		'section' => 'product-collection',
		'config'  => [
			'id'            => 'skincare',
			'eyebrow'       => 'عناية الوجه والرقبة',
			'heading_align' => 'center',
			'title'         => 'عالم العناية بالبشرة السريرية المركزة من نايا',
			'description'   => 'مستحضرات صيدلانية عالية النقاء تحل مشاكل البهتان، التصبغات، الخطوط التعبيرية والحاجز المتضرر بأعلى أمان.',
			'action_label'  => 'عرض الكل',
			'action_url'    => $categoryUrl('العناية-بالوجه'),
			'layout'        => 'grid',
			'show_rating'   => true,
			'source'        => ['type' => 'latest', 'limit' => 4, 'category' => 'العناية-بالوجه'],
		],
	],
	[
		'section' => 'product-collection',
		'config'  => [
			'id'            => 'bodycare',
			'eyebrow'       => 'طقوس السبا الفاخرة',
			'heading_align' => 'center',
			'title'         => 'طقوس العناية والترطيب العميق للجسم 48 ساعة',
			'description'   => 'صقل الجلد، تفتيح المناطق الجافة، والتخلص من مظهر جلد الوزة بتركيبات غنية بزبدة الشيا والقهوة وأحماض الألفا هيدروكسي.',
			'action_label'  => 'عرض الكل',
			'action_url'    => $categoryUrl('العناية-بالجسم'),
			'layout'        => 'grid',
			'show_rating'   => true,
			'source'        => ['type' => 'latest', 'limit' => 4, 'category' => 'العناية-بالجسم'],
		],
	],
	[
		'section' => 'product-collection',
		'config'  => [
			'id'            => 'bundles',
			'eyebrow'       => 'بكجات التوفير',
			'heading_align' => 'center',
			'title'         => 'بكجات نايا المتكاملة (وفري حتى 35%)',
			'description'   => 'روتينات مصممة لتعمل بتناغم تام، مضاعفة النتائج وموفرة لوقتك ولميزانيتك.',
			'action_label'  => 'عرض الكل',
			'action_url'    => $categoryUrl('kits'),
			'show_rating'   => true,
			'source'        => ['type' => 'latest', 'limit' => 8, 'category' => 'kits'],
		],
	],
	[
		'section' => 'product-collection',
		'config'  => [
			'id'            => 'bestsellers',
			'eyebrow'       => 'الأعلى تقييماً وطلباً',
			'heading_align' => 'center',
			'title'         => 'مختارات نايا الأكثر مبيعاً',
			'description'   => 'المنتجات اللي غيرت روتين آلاف العميلات وحصلت على أعلى التقييمات.',
			'layout'        => 'carousel',
			'show_rating'   => true,
			'source'        => ['type' => 'best-selling', 'limit' => 8],
		],
	],
];
