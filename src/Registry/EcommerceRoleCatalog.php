<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

/**
 * Canonical role ids for symfinity/ux-blocks-ecommerce (symfinity 027).
 *
 * @return list<string>
 */
final class EcommerceRoleCatalog
{
    /** @return list<string> */
    public static function roles(): array
    {
        return [
            'product-overview',
            'product-list-section',
            'product-card',
            'shopping-cart-layout',
            'checkout-form-section',
            'category-filters-static',
            'order-summary',
            'order-history',
            'promo-incentives',
            'cart-drawer-quickview',
        ];
    }

    public static function twigComponentForRole(string $role): string
    {
        return match ($role) {
            'product-overview' => 'ProductOverview',
            'product-list-section' => 'ProductListSection',
            'product-card' => 'ProductCard',
            'shopping-cart-layout' => 'ShoppingCartLayout',
            'checkout-form-section' => 'CheckoutFormSection',
            'category-filters-static' => 'CategoryFiltersStatic',
            'order-summary' => 'OrderSummary',
            'order-history' => 'OrderHistory',
            'promo-incentives' => 'PromoIncentives',
            'cart-drawer-quickview' => 'CartDrawerQuickview',
            default => throw new \InvalidArgumentException(sprintf('Unknown ecommerce role "%s"', $role)),
        };
    }

    public static function interactionForRole(string $role): string
    {
        return $role === 'cart-drawer-quickview' ? 'stl' : 'nat';
    }
}
