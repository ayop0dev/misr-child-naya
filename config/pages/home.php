<?php
/**
 * Naya homepage composition (replaces the Egstore theme's home.php).
 *
 * Sections, data and behaviour come from the Egstore theme and the commerce
 * plugin; this file only chooses them, in order, with this store's copy
 * (from the design in Egstore-theme/demo-home). Category cards use each
 * category's own thumbnail and link.
 *
 * Sections of the design the theme cannot render yet (flash deals with a
 * countdown, routine finder, shop by concern, bundle builder, clinical
 * proof, ingredients, reviews, UGC, blog, FAQ, newsletter) are added here as
 * the theme gains them; see README.md.
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
			'eyebrow'             => 'نايا للجمال النقي',
			'title'               => 'علم الطبيعة لجمال يدوم',
			'description'         => 'نجمع بين أنقى الخلاصات النباتية العضوية وأحدث ابتكارات طب الجلدية السريرية. خالٍ تماماً من البارابين، العطور الاصطناعية، والسيليكون لتمنحي نفسك عناية نقية تستحقينها.',
			'mobile_align'        => 'center',
			'search'              => true,
			'search_placeholder'  => 'ابحثي عن منتج أو مكون...',
			'search_button_label' => 'بحث',
			'action_label'        => 'تسوقي كافة الأقسام',
			'action_url'          => $shopUrl,
		],
	],
	[
		'section' => 'features',
		'config'  => [
			'id'    => 'pillars',
			'items' => [
				['icon' => 'truck', 'title' => 'شحن سريع ومبرد', 'text' => 'القاهرة والجيزة والإسكندرية خلال 24-48 ساعة، وخلال 3 أيام لباقي المحافظات.'],
				['icon' => 'shield-check', 'title' => 'ضمان الرضا 30 يوماً', 'text' => 'استرجاع أو استبدال إن لم تلحظي نتيجة إيجابية في بشرتك أو شعرك.'],
				['icon' => 'message-circle', 'title' => 'استشارة مجانية', 'text' => 'تحدثي معنا عبر واتساب لبناء روتينك الأمثل.'],
				['icon' => 'banknote', 'title' => 'طرق دفع مصرية متنوعة', 'text' => 'فودافون كاش، إنستاباي، فوري، كروت ميزة، أو الدفع عند الاستلام.'],
			],
		],
	],
	[
		'section' => 'category-showcase',
		'config'  => [
			'id'          => 'categories',
			'title'       => 'استكشفي حسب عالم العناية',
			'description' => 'تشكيلة شاملة مدروسة بعناية لتلبي جميع احتياجات روتينك اليومي.',
			'layout'      => 'grid',
			'categories'  => [
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
			'id'           => 'new-arrivals',
			'title'        => 'أحدث ابتكارات العناية والجمال من نايا',
			'description'  => 'تركيبات مطورة حديثاً بأعلى تركيزات الفعالية لتمنحك نتائج ملموسة.',
			'action_label' => 'عرض الكل',
			'action_url'   => $shopUrl,
			'show_rating'  => true,
			'source'       => ['type' => 'latest', 'limit' => 8],
		],
	],
	[
		'section' => 'product-collection',
		'config'  => [
			'id'           => 'deals',
			'title'        => 'تخفيضات الدفعة الحصرية: خصومات تصل إلى 40%',
			'description'  => 'دفعات طازجة تم إنتاجها حديثاً لضمان أقصى نشاط حيوي للمكونات. ينتهي العرض عند نفاد الكمية المخصصة.',
			'action_label' => 'عرض الكل',
			'action_url'   => $categoryUrl('offers'),
			'show_rating'  => true,
			'source'       => ['type' => 'on-sale', 'limit' => 8],
		],
	],
	[
		'section' => 'product-collection',
		'config'  => [
			'id'           => 'skincare',
			'title'        => 'عالم العناية بالبشرة من نايا',
			'description'  => 'مستحضرات عالية النقاء للبهتان والتصبغات والخطوط التعبيرية والحاجز المتضرر.',
			'action_label' => 'عرض الكل',
			'action_url'   => $categoryUrl('العناية-بالوجه'),
			'show_rating'  => true,
			'source'       => ['type' => 'latest', 'limit' => 8, 'category' => 'العناية-بالوجه'],
		],
	],
	[
		'section' => 'product-collection',
		'config'  => [
			'id'           => 'bodycare',
			'title'        => 'طقوس العناية والترطيب العميق للجسم',
			'description'  => 'صقل الجلد وتفتيح المناطق الجافة بتركيبات غنية بزبدة الشيا والقهوة.',
			'action_label' => 'عرض الكل',
			'action_url'   => $categoryUrl('العناية-بالجسم'),
			'show_rating'  => true,
			'source'       => ['type' => 'latest', 'limit' => 8, 'category' => 'العناية-بالجسم'],
		],
	],
	[
		'section' => 'product-collection',
		'config'  => [
			'id'           => 'bundles',
			'title'        => 'بكجات نايا المتكاملة (وفري حتى 35%)',
			'description'  => 'روتينات مصممة لتعمل بتناغم تام، مضاعفة النتائج وموفرة لوقتك ولميزانيتك.',
			'action_label' => 'عرض الكل',
			'action_url'   => $categoryUrl('kits'),
			'show_rating'  => true,
			'source'       => ['type' => 'latest', 'limit' => 8, 'category' => 'kits'],
		],
	],
	[
		'section' => 'product-collection',
		'config'  => [
			'id'           => 'bestsellers',
			'title'        => 'مختارات نايا الأكثر مبيعاً',
			'description'  => 'المنتجات اللي غيرت روتين آلاف العميلات وحصلت على أعلى التقييمات.',
			'action_label' => 'عرض الكل',
			'action_url'   => $shopUrl,
			'show_rating'  => true,
			'source'       => ['type' => 'best-selling', 'limit' => 8],
		],
	],
];
