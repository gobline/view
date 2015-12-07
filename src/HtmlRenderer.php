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
class HtmlRenderer implements ViewRendererInterface
{
    private $renderer;
    private $layoutRenderer;
    private $templateRenderer;
    private $layoutsEnabled = true;

    public function __construct(ViewHelperRegistry $viewHelperRegistry)
    {
        $this->templateRenderer = new HtmlTemplateRenderer($viewHelperRegistry);
        $this->layoutRenderer = new HtmlLayoutRenderer($this->templateRenderer, $viewHelperRegistry);
        $this->renderer = new HtmlMasterLayoutRenderer($this->layoutRenderer, $viewHelperRegistry);
    }

    public function render($template, $model)
    {
        if (!$this->layoutsEnabled) {
            return $this->templateRenderer->render($template, $model);
        }

        return $this->renderer->render($template, $model);
    }

    public function setLayouts(array $layouts)
    {
        return $this->layoutRenderer->setLayouts($layouts);
    }

    public function enableLayouts()
    {
        $this->layoutsEnabled = true;
    }

    public function disableLayouts()
    {
        $this->layoutsEnabled = false;
    }
}
