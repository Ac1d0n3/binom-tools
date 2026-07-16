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
        $layout = $parsed['layout'];
        $listItems = [];
        $stepCount = count($parsed['steps']);
        $useChevronShape = $variant === 'chevron' && $layout === 'horizontal';

        foreach ($parsed['steps'] as $index => $step) {
            $classes = [$useChevronShape ? 'playbook-flowchart__chevron' : 'playbook-flowchart__step'];

            if ($step['state'] === 'active') {
                $classes[] = 'playbook-flowchart__step--active';
            }

            if ($step['state'] === 'completed') {
                $classes[] = 'playbook-flowchart__step--completed';
            }

            $labelChildren = [
                new HtmlElement('span', ['class' => 'playbook-flowchart__num'], (string) ($index + 1)),
                new HtmlElement('span', ['class' => 'playbook-flowchart__label'], Xml::escape($step['label'])),
            ];

            // Chevron: outer shell = border color, inner face = fill (real Rahmen via padding).
            $stepNode = $useChevronShape
                ? new HtmlElement(
                    'span',
                    ['class' => implode(' ', $classes)],
                    [new HtmlElement('span', ['class' => 'playbook-flowchart__chevron-face'], $labelChildren)],
                )
                : new HtmlElement('span', ['class' => implode(' ', $classes)], $labelChildren);

            $itemChildren = [$stepNode];

            // Connectors: visible for vertical stacks and for stacked chevron fallback.
            if ($index < $stepCount - 1) {
                $itemChildren[] = new HtmlElement(
                    'span',
                    ['class' => 'playbook-flowchart__connector', 'aria-hidden' => 'true'],
                    '',
                );
            }

            // Div items — avoid .playbook-prose li + li margin shifting step 1.
            $listItems[] = new HtmlElement(
                'div',
                ['class' => 'playbook-flowchart__item', 'role' => 'listitem'],
                $itemChildren,
            );
        }

        return new HtmlElement(
            'nav',
            [
                'class' => 'playbook-flowchart playbook-flowchart--'.$variant.' playbook-flowchart--'.$layout,
                'aria-label' => 'Process flow',
            ],
            new HtmlElement(
                'div',
                ['class' => 'playbook-flowchart__list', 'role' => 'list'],
                $listItems,
            ),
        );
    }
}
