<?php

namespace TobiasOlry\Talkly\Twig;

use TobiasOlry\Talkly\Security\Security;

/**
 *
 * @author David Badura <d.a.badura@gmail.com>
 */
class SecurityExtension extends \Twig_Extension
{

    /**
     *
     * @var Security
     */
    protected $security;

    /**
     *
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     *
     * @return array
     */
    public function getGlobals()
    {
        return array(
            'security' => $this->security
        );
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'security';
    }
}
