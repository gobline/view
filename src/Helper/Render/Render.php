<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\Render;

use Gobline\View\Helper\ViewHelperInterface;
use Gobline\View\Helper\ViewHelperRegistry;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Render implements ViewHelperInterface
{
    private $viewHelperRegistry;

    public function __construct(ViewHelperRegistry $viewHelperRegistry)
    {
        $this->viewHelperRegistry = $viewHelperRegistry;
    }

    public static function getName()
    {
        return 'render';
    }

    public function __invoke($template, array $data = [], $context = null)
    {
        $render = function () use ($template, $data) {
            extract($this->viewHelperRegistry->getArrayCopy());
            extract($data);
            include $template;
        };
        if ($context) {
            $render = $render->bindTo($context);
        }

        ob_start();
        $render();
        $content = ob_get_clean();

        return $content;
    }
}
