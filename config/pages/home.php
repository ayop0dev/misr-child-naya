<?php
/**
 * Naya homepage composition (replaces the Egstore theme's home.php).
 *
 * Sections, data and behaviour come from the Egstore theme and the commerce
 * plugin; this file only chooses them, in order, with this store's copy
 * (from the design in Egstore-theme/demo-home). Category cards use each
 * category's own thumbnail and link.
 *
 * Copy follows what Naya sells (Moroccan and Sudanese care rituals, scrubs,
 * butters, bridal and pedicure boxes) and only promises what the store
 * confirmed: delivery in 5-7 working days, free shipping over 1000 ج.م, cash
 * on delivery, wallets, InstaPay and help on WhatsApp. The hero figures are
 * the store's. Sections of the design the theme cannot
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
			'eyebrow'                => 'وصفات طبيعية من طقوس الجمال المغربي والسوداني',
			'eyebrow_style'          => 'pill',
			'eyebrow_icon'           => 'leaf',
			'title'                  => 'طقوس جمال طبيعية لروتينك الكامل',
			'title_highlight'        => 'للبشرة والشعر والجسم',
			'description'            => 'صابون مغربي، ودلكة سودانية، ومخمريات، وسكرابات وزبدات مخفوقة، مختارة بعناية عشان تمنحك نعومة ودلال في بيتك. اطلبي من أي محافظة في مصر وادفعي عند الاستلام.',
			'mobile_align'           => 'center',
			// Shipped with the theme (assets/images/hero.webp, 960×1200, the
			// design's photo), so it is the same locally and on the store.
			'image'                  => get_stylesheet_directory_uri() . '/assets/images/hero.webp',
			'action_label'           => 'تسوقي كل المنتجات',
			'action_url'             => $shopUrl,
			'secondary_action_label' => 'العروض',
			'secondary_action_url'   => $categoryUrl('offers'),
			'stats'                  => [
				['value' => '98%', 'label' => 'مكونات طبيعية ونقية'],
				['value' => '+65,000', 'label' => 'عميلة راضية في محافظات مصر'],
				['value' => '4.9 / 5', 'label' => 'متوسط تقييم عميلاتنا'],
			],
			'badges'                 => [
				['icon' => 'heart', 'title' => 'تجهيزات العروسة', 'text' => 'بوكس العروسة وبوكسات الباديكير والهنا'],
				['icon' => 'truck', 'title' => 'شحن مجاني فوق 1000 ج.م', 'text' => 'توصيل لكل المحافظات خلال 5 إلى 7 أيام عمل'],
			],
		],
	],
	[
		'section' => 'features',
		'config'  => [
			'id'     => 'pillars',
			'layout' => 'inline',
			'items'  => [
				['icon' => 'truck', 'title' => 'شحن لكل المحافظات', 'text' => 'التوصيل خلال 5 إلى 7 أيام عمل، والشحن مجاني للطلبات فوق 1000 ج.م.'],
				['icon' => 'banknote', 'title' => 'ادفعي بالطريقة اللي تريحك', 'text' => 'الدفع عند الاستلام، أو بالمحفظة الإلكترونية، أو إنستاباي.'],
				['icon' => 'message-circle', 'title' => 'نساعدك تختاري على واتساب', 'text' => 'اسألينا عن أي منتج، وفريقنا يساعدك تختاري اللي يناسبك.'],
				['icon' => 'leaf', 'title' => 'مكونات طبيعية', 'text' => 'وصفات تقليدية بالطمي المغربي والعكر الفاسي والنيلة الزرقاء والزيوت الطبيعية.'],
			],
		],
	],
	[
		'section' => 'category-showcase',
		'config'  => [
			'id'            => 'categories',
			'eyebrow'       => 'أقسام المتجر',
			'heading_align' => 'center',
			'title'         => 'اختاري من أقسام نايا',
			'description'   => 'منتجات عناية للبشرة والشعر والجسم، وبوكسات جاهزة لكل مناسبة.',
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
			'eyebrow'       => 'عروض نايا',
			'heading_align' => 'center',
			'title'         => 'خصومات على منتجات مختارة',
			'description'   => 'أسعار مخفضة على منتجات من كل الأقسام، ونسبة الخصم مكتوبة على كل منتج.',
			'action_label'  => 'تسوقي كل العروض',
			'action_url'    => $categoryUrl('offers'),
			'layout'        => 'deals',
			'countdown_end' => '2026-11-30T23:59:59',
			'show_rating'   => true,
			'source'        => ['type' => 'on-sale', 'limit' => 2],
		],
	],
	[
		'section' => 'product-collection',
		'config'  => [
			'id'            => 'new-arrivals',
			'eyebrow'       => 'وصل حديثاً',
			'heading_align' => 'center',
			'title'         => 'أحدث منتجات نايا',
			'description'   => 'آخر المنتجات اللي وصلت المتجر.',
			'layout'        => 'carousel',
			'show_rating'   => true,
			'source'        => ['type' => 'latest', 'limit' => 8],
		],
	],
	[
		'section' => 'product-collection',
		'config'  => [
			'id'            => 'skincare',
			'eyebrow'       => 'العناية بالوجه',
			'heading_align' => 'center',
			'title'         => 'نعومة ونضارة لبشرتك وشفايفك',
			'description'   => 'من الطمي المغربي والحنة البيضاء وجل الورد، لحد الليب أويل وسيرم الرموش والحواجب.',
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
			'eyebrow'       => 'طقوس الحمام المغربي',
			'heading_align' => 'center',
			'title'         => 'عناية الجسم: تقشير وترطيب ونعومة',
			'description'   => 'صابون مغربي وليفة للتقشير، ودلكة سودانية وسكرابات، وبعدها زبدات ومخمريات وزيوت تسيب بشرتك ناعمة ومعطرة.',
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
			'eyebrow'       => 'مجموعات العناية',
			'heading_align' => 'center',
			'title'         => 'بوكسات نايا الجاهزة',
			'description'   => 'روتين كامل في بوكس واحد، ليكي أو كهدية: بوكس العروسة، والباديكير، والهنا، وغيرهم.',
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
			'eyebrow'       => 'الأكثر طلباً',
			'heading_align' => 'center',
			'title'         => 'الأكثر مبيعاً في نايا',
			'description'   => 'المنتجات اللي عميلاتنا بيرجعوا يطلبوها كل مرة.',
			'layout'        => 'carousel',
			'show_rating'   => true,
			'source'        => ['type' => 'best-selling', 'limit' => 8],
		],
	],
];
