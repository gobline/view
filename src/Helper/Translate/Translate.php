<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\Translate;

use Gobline\View\Helper\ViewHelperInterface;
use Gobline\Translator\Translator;
use Gobline\Environment\Environment;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Translate implements ViewHelperInterface
{
    private $translator;
    private $environment;

    public function __construct(Environment $environment, Translator $translator)
    {
        $this->translator = $translator;
        $this->environment = $environment;
    }

    public static function getName()
    {
        return 'translate';
    }

    public function __invoke($str, $params = null, $language = null)
    {
        if (!$language) {
            $language = $this->environment->getLanguage() ?: null;
        }

        return $this->translator->translate($str, $params, $language);
    }
}
