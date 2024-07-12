<?php

/**
 * Customizes the product price HTML.
 *
 * @param mixed $price The original price.
 * @param mixed $product The product object.
 * @return string The customized price HTML.
 */
function custom_mask_product_price_html($price, $product)
{
    $caseSize = $product->get_attribute('pa_case-size');
    if (!$caseSize || $product->get_price() == '') {
        return $price;
    }

   // Customize the price format as needed

    $pricePerUnit = number_format((float)$product->get_price() / (int)$caseSize, 2);
    $price = esc_html__('As low as: ', 'your-theme-text-domain') . '</span>' . wc_price($pricePerUnit) . '<span class="custom-price">/unit</span>';
    return $price;
}

add_filter('woocommerce_get_price_html', 'custom_mask_product_price_html', 10, 2);