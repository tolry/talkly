<?php

namespace TobiasOlry\Talkly\Twig;

use TobiasOlry\Talkly\Entity\User;

/**
 *
 * @author David Badura <d.a.badura@gmail.com>
 */
class GravatarExtension extends \Twig_Extension
{
    /**
     *
     * @var string
     */
    const URL = 'http://www.gravatar.com/avatar/{hash}?d=mm&s={size}';

    /**
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('gravatar', array($this, 'gravatar'), array('is_safe' => array('html'))),
        );
    }

    /**
     *
     * @param User $user
     * @return string
     */
    public function gravatar(User $user, $size = 80)
    {
        return strtr(self::URL, array(
            '{hash}' => $this->getHash($user),
            '{size}' => $size
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'gravatar';
    }

    /**
     *
     * @return string
     */
    private function getHash(User $user)
    {
        return md5(strtolower(trim($user->getEmail())));
    }
}
