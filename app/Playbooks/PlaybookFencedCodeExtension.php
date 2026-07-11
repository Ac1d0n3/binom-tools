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

final class PlaybookFencedCodeExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(FencedCode::class, new PlaybookFencedCodeRenderer, 10);
    }
}

final class PlaybookFencedCodeRenderer implements NodeRendererInterface
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
        $meta = PlaybookCodeFenceParser::parse($info);
        $language = $meta['language'] !== '' ? $meta['language'] : 'text';

        $attrs = [
            'class' => 'playbook-code',
            'data-language' => $language,
        ];

        if ($meta['title'] !== null) {
            $attrs['data-title'] = $meta['title'];
        }

        if ($meta['highlight'] !== null) {
            $attrs['data-highlight'] = $meta['highlight'];
        }

        $preAttrs = ['class' => 'line-numbers'];

        if ($meta['highlight'] !== null) {
            $preAttrs['data-line'] = $meta['highlight'];
        }

        $codeAttrs = ['class' => 'language-'.Xml::escape($language)];

        $codeElement = new HtmlElement('code', $codeAttrs, Xml::escape($node->getLiteral() ?? ''));
        $preElement = new HtmlElement('pre', $preAttrs, $codeElement);

        return new HtmlElement('div', $attrs, $preElement);
    }
}
