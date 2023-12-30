<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

class MoodleFormatService
{

    public static function formatUserCourses($enrollments, $customer, $filtered_products = null)
    {
        $base_url = config("moodle.moodle_address");
        return collect($enrollments)
        // ->filter(function ($item) use ($filtered_products) {
        //     if (!$filtered_products) {
        //         return false;
        //     }
        //     return !$filtered_products->firstWhere('moodle_id', $item['id']);
        // })
        ->map(function ($item) use ($base_url, $customer, $filtered_products) {
            //$item['moodle_url'] = $base_url.'/auth/userkey/login.php?key='.$customer->moodle_login_key
            //    .'&wantsurl='.$base_url.'/course/view.php?id='.$item['id'];
            $item['moodle_url'] = route('customer.moodle.redirect', ['course_id'=>$item['id']]);

            if (isset($item['summary_files'][0]['url'])) {
                $item['image'] = $item['summary_files'][0]['url'];
                return $item;
            }
            $product = $filtered_products->firstWhere('moodle_id', $item['id']);
            $item['image'] = $product
            ? productimage()->getProductBaseImage($product)['medium_image_url']
            : asset('vendor/webkul/ui/assets/images/product/meduim-product-placeholder.webp');

            return $item;
        });
    }

    public static function formatProduct(Collection $products, $customer)
    {
        $base_url = config("moodle.moodle_address");

        return $products->map(function ($product) use ($base_url, $customer) {
            //$item['moodle_url'] = $base_url.'/auth/userkey/login.php?key='.$customer->moodle_login_key
            //    .'&wantsurl='.$base_url.'/course/view.php?id='.$product->moodle_id;
            $item['moodle_url'] = route('customer.moodle.redirect', ['course_id' => $product->moodle_id]);
            $item['fullname'] = $product->short_name;
            $item['image'] = productimage()->getProductBaseImage($product)['medium_image_url'];
            return $item;
        });
    }
}
