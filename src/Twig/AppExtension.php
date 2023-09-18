<?php

namespace App\Twig;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $publicUrl;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->publicUrl = '/public';
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('encore_entry_link_tags', [$this, 'encoreEntryLinkTags'], ['is_safe' => ['html']]),
        ];
    }

    public function encoreEntryLinkTags(string $entryName): string
    {
        $linkTags = '';
        $cssFiles = [];
        $manifestPath = $this->publicUrl . '/build/manifest.json';

        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
            $cssFiles = $manifest[$entryName]['css'] ?? [];
        }

        foreach ($cssFiles as $cssFile) {
            $assetUrl = $this->publicUrl . $cssFile;
            $linkTags .= sprintf('<link rel="stylesheet" href="%s">', htmlspecialchars($assetUrl, ENT_QUOTES, 'UTF-8'));
        }

        return $linkTags;
    }
}
