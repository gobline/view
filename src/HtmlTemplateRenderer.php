<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View;

use Gobline\View\Helper\ViewHelperContainer;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class HtmlTemplateRenderer implements ViewRendererInterface
{
    use ViewHelperTrait;

    public function __construct(ViewHelperContainer $helperContainer = null)
    {
        if ($helperContainer) {
            $this->setHelperContainer($helperContainer);
        }
    }

    public function partial($template, array $data = [])
    {
        $render = function () use ($template, $data) {
            extract($this->getViewHelpers());
            extract($data);
            include $template;
        };

        ob_start();
        try {
            render();
        } finally {
            $content = ob_get_clean();
        }

        return $content;
    }

    public function render($template, $model)
    {
        $render = function () use ($model, $template) {
            extract($this->getViewHelpers());
            include $template;
        };
        ob_start();
        try {
            $render();
        } finally {
            $content = ob_get_clean();
        }

        return $content;
    }
}
