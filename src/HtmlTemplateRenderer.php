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

use Gobline\View\Helper\ViewHelperRegistry;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class HtmlTemplateRenderer implements ViewRendererInterface
{
    private $viewHelperRegistry;

    public function __construct(ViewHelperRegistry $viewHelperRegistry)
    {
        $this->viewHelperRegistry = $viewHelperRegistry;
    }

    public function partial($template, array $data = [])
    {
        $render = function () use ($template, $data) {
            extract($this->viewHelperRegistry->getArrayCopy());
            extract($data);
            include $template;
        };

        ob_start();
        $render();
        $content = ob_get_clean();

        return $content;
    }

    public function render($template, $model)
    {
        if (!$template) {
            return '';
        }

        $render = function () use ($model, $template) {
            extract($this->viewHelperRegistry->getArrayCopy());
            include $template;
        };

        ob_start();
        $render();
        $content = ob_get_clean();

        return $content;
    }
}
