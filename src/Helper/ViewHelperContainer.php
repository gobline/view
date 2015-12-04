<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper;

use Gobline\Container\Container;
use Gobline\Container\ContainerInterface;
use Gobline\Mediator\EventDispatcher;
use Gobline\Application\Middleware\MiddlewareDispatcher;
use Gobline\View\Helper\BasePath\BasePath;
use Gobline\View\Helper\Asset\Css\Css;
use Gobline\View\Helper\Asset\Collection\CssCollection;
use Gobline\View\Helper\Asset\Collection\CssCollectionFactory;
use Gobline\View\Helper\Description\Description;
use Gobline\View\Helper\Escape\Escape;
use Gobline\View\Helper\Flash\Flash;
use Gobline\View\Helper\Form\Form;
use Gobline\View\Helper\Hreflang\Hreflang;
use Gobline\View\Helper\Identity\Identity;
use Gobline\View\Helper\Asset\Js\Js;
use Gobline\View\Helper\Asset\Collection\JsCollection;
use Gobline\View\Helper\Asset\Collection\JsCollectionFactory;
use Gobline\View\Helper\Lang\Lang;
use Gobline\View\Helper\Meta\Meta;
use Gobline\View\Helper\NoIndex\NoIndex;
use Gobline\View\Helper\Placeholder\Placeholder;
use Gobline\View\Helper\Responsive\Responsive;
use Gobline\View\Helper\Route\Route;
use Gobline\View\Helper\Title\Title;
use Gobline\View\Helper\Translate\Translate;
use Gobline\View\Helper\Asset\Minifier;
use Gobline\Environment\Environment;
use Gobline\Flash\Flash as FlashComponent;
use Gobline\Translator\Translator as TranslatorComponent;
use Gobline\Router\UriBuilder;
use Gobline\Auth\CurrentUser;
use Zend\Escaper\Escaper;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class ViewHelperContainer extends Container
{
    private $eventDispatcher;
    private $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();

        $this->container = $container;

        $this->init();
    }

    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    public function init()
    {
        $this->eventDispatcher = new EventDispatcher();

        $this->set(BasePath::class, function () {
            return new BasePath($this->container->get(Environment::class));
        });

        $this->set(Css::class, function () {
            return new Css($this->eventDispatcher, $this->container->get(Environment::class), new Minifier());
        }, false);

        $this->set(CssCollection::class, function () {
            return new CssCollectionFactory($this->eventDispatcher, $this->container->get(Environment::class), new Minifier());
        }, false);

        $this->set(Description::class, function () {
            return new Description($this->container->get(Environment::class));
        });

        $this->set(Escape::class, function () {
            return new Escape(new Escaper());
        });

        $this->set(Flash::class, function () {
            return new Flash($this->container->get(FlashComponent::class));
        });

        $this->set(Form::class, function () {
            return new Form($this->container->get(TranslatorComponent::class), $this->container->get(Environment::class));
        });

        $this->set(Hreflang::class, function () {
            return new Hreflang($this->eventDispatcher, $this->container->get(Environment::class), $this->container->get(UriBuilder::class));
        });

        $this->set(Identity::class, function () {
            return new Identity($this->container->get(CurrentUser::class));
        });

        $this->set(Js::class, function () {
            return new Js($this->eventDispatcher, $this->container->get(Environment::class), new Minifier());
        }, false);

        $this->set(JsCollection::class, function () {
            return new JsCollectionFactory($this->eventDispatcher, $this->container->get(Environment::class), new Minifier());
        }, false);

        $this->set(Lang::class, function () {
            return new Lang($this->eventDispatcher, $this->container->get(Environment::class));
        });

        $this->set(Meta::class, function () {
            return new Meta($this->eventDispatcher);
        });

        $this->set(NoIndex::class, function () {
            return new NoIndex($this->eventDispatcher);
        });

        $this->set(Placeholder::class, function () {
            return new Placeholder();
        });

        $this->set(Responsive::class, function () {
            return new Responsive($this->eventDispatcher);
        });

        $this->set(Route::class, function () {
            return new Route($this->container->get(Environment::class), $this->container->get(UriBuilder::class), $this->container->get(MiddlewareDispatcher::class));
        });

        $this->set(Title::class, function () {
            return new Title($this->eventDispatcher);
        });

        $this->set(Translate::class, function () {
            return new Translate($this->container->get(Environment::class), $this->container->get(TranslatorComponent::class));
        });
    }
}
