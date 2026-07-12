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

final class PlaybookFlowchartFenceExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(FencedCode::class, new PlaybookFlowchartFenceRenderer, 30);
    }
}

final class PlaybookFlowchartFenceRenderer implements NodeRendererInterface
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

        if (! PlaybookFlowchartParser::isFlowchartFence($info)) {
            return (new PlaybookVideoFenceRenderer)->render($node, $childRenderer);
        }

        $parsed = PlaybookFlowchartParser::parse($info, trim($node->getLiteral() ?? ''));

        if ($parsed === null) {
            return new HtmlElement(
                'div',
                ['class' => 'playbook-flowchart playbook-flowchart--error'],
                Xml::escape('Flowchart needs at least two steps.'),
            );
        }

        $variant = $parsed['variant'];
        $listItems = [];

        foreach ($parsed['steps'] as $index => $step) {
            $classes = ['playbook-flowchart__step'];

            if ($variant === 'chevron') {
                $classes[0] = 'playbook-flowchart__chevron';
            }

            if ($step['state'] === 'active') {
                $classes[] = 'playbook-flowchart__step--active';
            }

            if ($step['state'] === 'completed') {
                $classes[] = 'playbook-flowchart__step--completed';
            }

            $children = [
                new HtmlElement('span', ['class' => 'playbook-flowchart__num'], (string) ($index + 1)),
                new HtmlElement('span', ['class' => 'playbook-flowchart__label'], Xml::escape($step['label'])),
            ];

            $listItems[] = new HtmlElement(
                'li',
                ['class' => 'playbook-flowchart__item'],
                new HtmlElement('span', ['class' => implode(' ', $classes)], $children),
            );
        }

        return new HtmlElement(
            'nav',
            [
                'class' => 'playbook-flowchart playbook-flowchart--'.$variant,
                'aria-label' => 'Process flow',
            ],
            new HtmlElement('ol', ['class' => 'playbook-flowchart__list'], $listItems),
        );
    }
}
