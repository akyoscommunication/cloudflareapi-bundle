<?php

namespace Akyos\CloudflareapiBundle\Service;

use Symfony\Contracts\Translation\TranslatableInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Menu\RouteMenuItem;

class CloudflareItem
{
    private function __construct()
    {
        
    }
    
    public static function linkToRoute(TranslatableInterface|string $label = 'CloudFlare API', ?string $icon = 'fab fa-cloudflare', array $routeParematers = []): RouteMenuItem
    {
        return new RouteMenuItem($label, $icon, 'admin_cloudflare_index', $routeParematers);
    }
}
