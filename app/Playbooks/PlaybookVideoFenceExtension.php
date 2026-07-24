<?php

namespace App\Playbooks;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Util\Xml;

final class PlaybookVideoFenceExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(FencedCode::class, new PlaybookVideoFenceRenderer, 20);
    }
}

final class PlaybookVideoFenceRenderer implements NodeRendererInterface
{
    /**
     * @param  FencedCode  $node
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): HtmlElement
    {
        if (! $node instanceof FencedCode) {
            throw new \InvalidArgumentException('Incompatible node type: '.get_class($node));
        }

        $info = trim($node->getInfo() ?? '');
        $language = strtolower(explode(' ', $info)[0] ?? '');

        if ($language !== 'video') {
            return (new PlaybookFencedCodeRenderer)->render($node, $childRenderer);
        }

        $url = trim($node->getLiteral() ?? '');
        $title = trim(preg_replace('/^video\s*/i', '', $info) ?? '') ?: null;
        $resolved = PlaybookVideoEmbedResolver::resolve($url);

        if ($resolved === null) {
            $message = $url === ''
                ? 'Missing video URL.'
                : 'Unsupported or invalid video URL.';

            return new HtmlElement('div', ['class' => 'playbook-video playbook-video--error'], Xml::escape($message));
        }

        if (($resolved['platform'] ?? '') === 'local') {
            return self::renderLocalVideo($resolved, $title);
        }

        $embedSrc = $resolved['privacyEmbedUrl'] ?? $resolved['embedUrl'];
        $attrs = [
            'class' => 'playbook-video',
            'data-video-embed' => 'true',
            'data-platform' => $resolved['platform'],
            'data-embed-src' => $embedSrc,
        ];

        if ($title !== null && $title !== '') {
            $attrs['data-video-title'] = $title;
        }

        $label = $title ?? ucfirst($resolved['platform']).' video';

        $button = new HtmlElement(
            'button',
            [
                'type' => 'button',
                'class' => 'playbook-video__consent-trigger',
            ],
            Xml::escape('Load video: '.$label),
        );

        return new HtmlElement('div', $attrs, $button);
    }

    /**
     * @param  array{platform: string, id: string, srcUrl?: string, mimeType?: string, publicPath?: string}  $resolved
     */
    private static function renderLocalVideo(array $resolved, ?string $title): HtmlElement
    {
        $src = $resolved['srcUrl'] ?? '';
        $mime = $resolved['mimeType'] ?? 'video/mp4';

        $videoAttrs = [
            'class' => 'playbook-video__player',
            'controls' => 'controls',
            'preload' => 'metadata',
            'playsinline' => 'playsinline',
        ];

        if ($title !== null && $title !== '') {
            $videoAttrs['title'] = $title;
        }

        $source = new HtmlElement('source', [
            'src' => $src,
            'type' => $mime,
        ]);

        $video = new HtmlElement('video', $videoAttrs, $source);

        $attrs = [
            'class' => 'playbook-video playbook-video--local',
            'data-video-local' => 'true',
            'data-platform' => 'local',
            'data-video-src' => $src,
        ];

        if ($title !== null && $title !== '') {
            $attrs['data-video-title'] = $title;
        }

        return new HtmlElement('div', $attrs, $video);
    }
}
