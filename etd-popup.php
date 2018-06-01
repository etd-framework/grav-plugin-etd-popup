<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class EtdPopupPlugin
 * @package Grav\Plugin
 */
class EtdPopupPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        // Enable the main event we are interested in
        $this->enable([
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
        ]);
    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    /**
     * if enabled on this page, load the JS + CSS theme.
     */
    public function onTwigSiteVariables()
    {
        $twig = $this->grav['twig'];

        $config = $this->config->toArray();

        $this->grav['assets']->addCss("https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.2.0/css/iziToast.min.css");
        $this->grav['assets']->addJs("https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.2.0/js/iziToast.min.js");

        $this->grav['assets']->addInlineCss('.iziToast-cover{width:100% !important;z-index:-1;background-size:contain !important}');
        $this->grav['assets']->addInlineJs($twig->twig->render('partials/etd-popup.html.twig', array('config' => $config)));
    }
}
