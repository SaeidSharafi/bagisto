<?php

namespace Webkul\Product\Helpers;

use Illuminate\Support\Facades\Storage;

class SEO
{
    /**
     * Returns product json ld data for product
     *
     * @param  \Webkul\Product\Contracts\Product|\Webkul\Product\Contracts\ProductFlat  $product
     *
     * @return array
     */
    public function getProductJsonLd($product)
    {
        $productViewHelper = app()->make(View::class);
        $customAttributeValues = $productViewHelper->getAdditionalData($product);
        $isOnline = false;
        $course_mode = 'Onsite';
        $location = 'Course Location';
        $workLoad = 'PT0S';
        collect($customAttributeValues)->each(function ($value, $key) use (
            &$workLoad,
            &$course_mode,
            &$location
        ) {
            if ($value['code'] == "course_type") {
                $isOnline = ($value['value'] === "مجازی");
                $course_mode = $isOnline ?
                    'Online' : 'Onsite';
            }
            if ($value['code'] == "class_length") {

                $numbers = preg_replace('/\D/', '', $value['value']);;

                $workLoad = "PT{$numbers}H";
            }
            if ($value['code'] == "location") {
                $location = [
                    "@type"   => "Place",
                    "name"    => $location ?? 'Course Location',
                    "address" => $location ?? 'Course Address'
                ];
            }

        });

        $teacher = collect($customAttributeValues)->filter(function ($value, $key) {
            return $value['group'] == "teacher_detail";
        })->pluck('value', 'code');

        $data = [
            '@context'            => 'https://schema.org/',
            '@type'               => 'Course',
            'name'                => $product->name,
            'description'         => $product->meta_description,
            'url'                 => route('shop.productOrCategory.index', $product->url_key),
            "provider"            => [
                "@type"       => "Person",
                "name"        => $teacher['teacher_name'],
                "description" => $teacher['teacher_bio'],
                "image"       => $teacher['teacher_image'] ? Storage::url($teacher['teacher_image'])
                    : config('app.url')."/images/teacher-sample.jpg"
            ],
            "offers"              => [
                "@type"         => "Offer",
                "url"           => route('shop.productOrCategory.index', $product->url_key),
                'category'      => "Paid",
                "priceCurrency" => core()->getCurrency()->code,
                "availability"  => "http://schema.org/InStock",
                "price"         => $product->getTypeInstance()->getMaximamPrice(),

            ],
            "courseMode"          => $course_mode,
            "coursePrerequisites" => "None",
            "hasCourseInstance"   => [
                "@type"          => "CourseInstance",
                "courseMode"     => $course_mode,
                "courseWorkload" => $workLoad,
                "instructor"     => [
                    "@type" => "Person",
                    "name"  => $teacher['teacher_name'] ?? '',
                ],

                "location" => $isOnline ? null : $location
            ],
            "audience"            => [
                "@type"        => "Audience",
                "audienceType" => "Students"
            ]
        ];

        if (core()->getConfigData('catalog.rich_snippets.products.show_sku')) {
            $data['sku'] = $product->sku;
        }

        if (core()->getConfigData('catalog.rich_snippets.products.show_categories')) {
            $data['category'] = $this->getProductCategories($product);
        }

        if (core()->getConfigData('catalog.rich_snippets.products.show_images')) {
            $data['image'] = $this->getProductImages($product);
        }

        if (core()->getConfigData('catalog.rich_snippets.products.show_reviews')) {
            $data['review'] = $this->getProductReviews($product);
        }

        if (core()->getConfigData('catalog.rich_snippets.products.show_ratings')) {
            $data['aggregateRating'] = $this->getProductAggregateRating($product);
        }

        if (core()->getConfigData('catalog.rich_snippets.products.show_offers')) {
            $data['offers'] = $this->getProductOffers($product);
        }

        return json_encode($data);
    }

    public function getPageJsonLd($name = null, $url = null, $description = null)
    {
        $channel = core()->getCurrentChannel();
        $homeSEO = json_decode($channel->home_seo);

        $homePageSchema = [
            "@context"        => "https://schema.org",
            "@type"           => "WebPage",
            "name"            => $name ?? $homeSEO->meta_title,
            "url"             => $url ?? url('/'),
            "description"     => $description ??
                "Jedu offers a variety of online and offline courses in multiple fields such as engineering, computer science, architecture, arts, and more.",
            "inLanguage"      => "fa",
            "potentialAction" => [
                "@type"       => "SearchAction",
                "target"      => config('app.url')."/categorysearch?term={search_term_string}",
                "query-input" => "required name=search_term_string",
            ],
            "breadcrumb"      => [
                "@type"           => "BreadcrumbList",
                "itemListElement" => [
                    [
                        "@type"    => "ListItem",
                        "position" => 1,
                        "name"     => "خانه",
                        "item"     => "https://jedu.ir"
                    ],
                    [
                        "@type"    => "ListItem",
                        "position" => 2,
                        "name"     => "درباره ما",
                        "item"     => "https://jedu.ir/about-us"
                    ],
                    [
                        "@type"    => "ListItem",
                        "position" => 3,
                        "name"     => "تماس با ما",
                        "item"     => "https://jedu.ir/contact-us"
                    ],
                    [
                        "@type"    => "ListItem",
                        "position" => 4,
                        "name"     => "بلاگ",
                        "item"     => "https://blog.jedu.ir"
                    ],
                    [
                        "@type"    => "ListItem",
                        "position" => 5,
                        "name"     => "ورود / ثبت‌نام",
                        "item"     => "https://jedu.ir/customer/login-register"
                    ]
                ]
            ],
            "mainEntity"      => [
                "@type" => "ItemList",
            ],
        ];

        $homePageSchema = array_merge(
            $homePageSchema,
            $this->getCategoryItems()
        );

        return json_encode($homePageSchema);
    }

    public function getCategoryItems()
    {
        $categoryRepository = app(\Webkul\Category\Repositories\CategoryRepository::class);
        $categories = $categoryRepository->getCategoryTree();
        $homePageSchema = [];
        foreach ($categories->first()->children as $key => $category) {

            if (!$category->status) {
                continue;
            }
            $categorySnippet = [
                "@type"    => "ListItem",
                "position" => $key + 1,
                "name"     => $category->name,
                "url"      => url('/'.$category->url_key),
            ];
            if ($category->children->count()) {
                foreach ($category->children as $subKey => $subCategory) {
                    $categorySnippet['itemListElement'][] = [
                        "@type"    => "ListItem",
                        "position" => $subKey + 1,
                        "name"     => $subCategory->name,
                        "url"      => url('/'.$subCategory->url_key),
                    ];
                }
            }
            $homePageSchema['mainEntity']['itemListElement'][] = $categorySnippet;
        }
        return $homePageSchema;
    }

    /**
     * Returns product categories
     *
     * @param  \Webkul\Product\Contracts\Product|\Webkul\Product\Contracts\ProductFlat  $product
     *
     * @return string
     */
    public function getProductCategories($product)
    {
        if ($product instanceof \Webkul\Product\Models\ProductFlat) {
            $categories = $product->product->categories;
        } else {
            $categories = $product->categories;
        }

        $names = [];

        foreach ($categories as $key => $category) {
            $names[] = $category->name;
        }

        return implode(', ', $names);
    }

    /**
     * Returns product images
     *
     * @param  \Webkul\Product\Contracts\Product|\Webkul\Product\Contracts\ProductFlat  $product
     *
     * @return array
     */
    public function getProductImages($product)
    {
        $images = [];

        foreach ($product->images as $image) {
            if (!Storage::has($image->path)) {
                continue;
            }

            $images[] = $image->url;
        }

        return $images;
    }

    /**
     * Returns product reviews
     *
     * @param  \Webkul\Product\Contracts\Product|\Webkul\Product\Contracts\ProductFlat  $product
     *
     * @return array
     */
    public function getProductReviews($product)
    {
        $reviews = [];

        foreach ($product->reviews()->where('status', 'approved')->get() as $review) {
            $reviews[] = [
                '@type'        => 'Review',
                'reviewRating' => [
                    '@type'       => 'Rating',
                    'ratingValue' => $review->rating,
                    'bestRating'  => '5',
                ],
                'author'       => [
                    '@type' => 'Person',
                    'name'  => $review->name,
                ],
            ];
        }

        return $reviews;
    }

    /**
     * Returns product average ratings
     *
     * @param  \Webkul\Product\Contracts\Product|\Webkul\Product\Contracts\ProductFlat  $product
     *
     * @return array
     */
    public function getProductAggregateRating($product)
    {
        $reviewHelper = app('Webkul\Product\Helpers\Review');

        return [
            '@type'       => 'AggregateRating',
            'ratingValue' => $reviewHelper->getAverageRating($product),
            'reviewCount' => $reviewHelper->getTotalReviews($product),
        ];
    }

    /**
     * Returns product average ratings
     *
     * @param  \Webkul\Product\Contracts\Product|\Webkul\Product\Contracts\ProductFlat  $product
     *
     * @return array
     */
    public function getProductOffers($product)
    {
        return [
            '@type'         => 'Offer',
            'priceCurrency' => core()->getCurrentCurrencyCode(),
            'price'         => $product->getTypeInstance()->getMinimalPrice(),
            'availability'  => 'https://schema.org/InStock',
        ];
    }

    /**
     * Returns product json ld data for category
     *
     * @param  \Webkul\Category\Contracts\Category  $category
     *
     * @return array
     */
    public function getCategoryJsonLd($category)
    {
        $data = [
            '@type'    => 'WebSite',
            '@context' => 'http://schema.org',
            'url'      => config('app.url'),
        ];

        if (core()->getConfigData('catalog.rich_snippets.categories.show_search_input_field')) {
            $data['potentialAction'] = [
                '@type'       => 'SearchAction',
                'target'      => config('app.url').'/search/?term={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ];
        }

        return json_encode($data);
    }
}