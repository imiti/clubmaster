<?php

namespace Club\UserBundle\Listener;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class LoginListener
{
  protected $container;
  protected $em;
  protected $security_context;
  protected $session;
  protected $request;

  public function __construct($container)
  {
    $this->container = $container;
    $this->em = $container->get('doctrine.orm.entity_manager');
    $this->security_context = $container->get('security.context');
    $this->session = $container->get('session');
    $this->request = $this->container->get('request');
  }

  public function onSecurityInteractiveLogin()
  {
    $user = $this->security_context->getToken()->getUser();

    $login = new \Club\UserBundle\Entity\LoginAttempt();
    $login->setUsername($user->getUsername());
    $login->setSession(session_id());
    $login->setIpAddress($this->request->getClientIp());
    $login->setHostname(gethostbyaddr($this->request->getClientIp()));
    $login->setLoginFailed(0);

    $this->em->persist($login);
    $this->em->flush();

    $this->setLocation();
    $this->setLocale();
    $this->checkin();
  }

  private function setLocale()
  {
    if ($this->security_context->isGranted('IS_AUTHENTICATED_FULLY') && ($locale = $this->security_context->getToken()->getUser()->getLocale()))
      $this->session->setLocale($locale);

    if ($this->session->getLocale())
      return;
  }

  private function setLocation()
  {
    if ($this->session->get('location_id')) return;

    $this->location = $this->em->getRepository('ClubUserBundle:Location')->getFirstLocation();
    if (!$this->location) return;

    $this->session->set('location_id', $this->location->getId());
    $this->session->set('location_name', $this->location->getLocationName());

    if (!$this->security_context->isGranted('IS_AUTHENTICATED_FULLY'))
      return;

    $user = $this->security_context->getToken()->getUser();

    if ($user instanceOf \Club\UserBundle\Entity\User) {
      $user->setLocation($this->location);
      $this->em->persist($user);
      $this->em->flush();
    }
  }

  private function checkin()
  {
    $allowed_ip = $this->container->getParameter('club_checkin.allowed_ip');

    foreach ($allowed_ip as $ip) {
      if ($ip == $this->request->getClientIp()) {
        $this->container->get('club_checkin.checkin')->checkin();
      }
    }
  }
}
