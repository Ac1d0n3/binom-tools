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
}
