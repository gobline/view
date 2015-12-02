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
class HtmlLayoutRenderer implements ViewRendererInterface
{
    use ViewHelperTrait;

    private $htmlRenderer;
    private $layouts;
    private $template;
    private $model;

    public function __construct(ViewRendererInterface $htmlRenderer, ViewHelperContainer $helperContainer = null)
    {
        $this->htmlRenderer = $htmlRenderer;

        if ($helperContainer) {
            $this->setHelperContainer($helperContainer);
        }
    }

    public function render($template, $model)
    {
        $this->template = $template;
        $this->model = $model;

        return $this->content();
    }

    public function content()
    {
        if ($this->layouts) {
            $layout = array_shift($this->layouts);

            $render = function() use ($layout) {
                extract($this->getViewHelpers());
                include $layout;
            };

            ob_start();
            try {
                $render();
            } finally {
                $content = ob_get_clean();
            }

            return $content;
        }

        return $this->htmlRenderer->render($this->template, $this->model);
    }

    public function setLayouts(array $layouts)
    {
        $this->layouts = $layouts;
    }
}
