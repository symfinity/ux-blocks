<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

/**
 * Canonical role ids for symfinity/ux-blocks-marketing (symfinity 026).
 *
 * @return list<string>
 */
final class MarketingRoleCatalog
{
    /** @return list<string> */
    public static function roles(): array
    {
        return [
            'hero',
            'feature-section',
            'cta-band',
            'pricing-section',
            'landing-page',
            'testimonials',
            'newsletter',
            'footer',
            'stats-band',
            'logo-cloud',
            'faq',
            'team',
            'content-section',
            'bento-grid',
            'banner',
            'header-marketing',
            'flyout-menu-marketing',
            'error-page-404',
        ];
    }

    public static function twigComponentForRole(string $role): string
    {
        return match ($role) {
            'hero' => 'Hero',
            'feature-section' => 'FeatureSection',
            'cta-band' => 'CtaBand',
            'pricing-section' => 'PricingSection',
            'landing-page' => 'LandingPage',
            'testimonials' => 'Testimonials',
            'newsletter' => 'Newsletter',
            'footer' => 'Footer',
            'stats-band' => 'StatsBand',
            'logo-cloud' => 'LogoCloud',
            'faq' => 'Faq',
            'team' => 'Team',
            'content-section' => 'ContentSection',
            'bento-grid' => 'BentoGrid',
            'banner' => 'Banner',
            'header-marketing' => 'HeaderMarketing',
            'flyout-menu-marketing' => 'FlyoutMenuMarketing',
            'error-page-404' => 'ErrorPage404',
            default => throw new \InvalidArgumentException(sprintf('Unknown marketing role "%s"', $role)),
        };
    }
}
