<?php

namespace Club\MatchBundle\Listener;

class MenuListener
{
    private $router;
    private $security_context;
    private $translator;

    public function __construct($router, $security_context, $translator)
    {
        $this->router = $router;
        $this->security_context = $security_context;
        $this->translator = $translator;
    }

    public function onTopMenuRender(\Club\MenuBundle\Event\FilterMenuEvent $event)
    {
        $menu[100] = array(
            'header' => $this->translator->trans('Matches'),
            'items' => array(
                array(
                    'name' => $this->translator->trans('Matches'),
                    'route' => $this->router->generate('club_match_match_index'),
                )
            )
        );

    $event->appendMenu($menu);
    }

    public function onLeftMenuRender(\Club\MenuBundle\Event\FilterMenuEvent $event)
    {
        if ($this->security_context->isGranted('ROLE_MATCH_ADMIN')) {
            $menu[23] = array(
                'header' => $this->translator->trans('Match'),
                'image' => 'bundles/clublayout/images/icons/16x16/medal_gold_1.png'
            );

            $event->appendMenu($menu);
        }
    }
}
