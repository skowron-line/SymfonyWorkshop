<?php

/*
 * This file is part of the SymfonyWorkshop package.
 *
 * (C) Alan Gabriel Bem <alan.bem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GoldenLine\UserBundle\Security\Core\User;

use GoldenLine\UserBundle\Model\User;
use GoldenLine\UserBundle\Model\UserPeer;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Bridge\Propel1\Security\User\PropelUserProvider as BaseUserProvider;

/**
 * OAuthPropelUserProvider class.
 *
 * @author Alan Gabriel Bem <alan.bem@goldenline.pl>
 */
class OAuthPropelUserProvider extends BaseUserProvider implements OAuthAwareUserProviderInterface
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(UserPeer::OM_CLASS, UserPeer::translateFieldName(UserPeer::EMAIL, \BasePeer::TYPE_COLNAME, \BasePeer::TYPE_PHPNAME));
    }

    /**
     * Loads the user by a given UserResponseInterface object.
     *
     * @param UserResponseInterface $response
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        try {
            return $this->loadUserByUsername($response->getEmail());
        } catch (UsernameNotFoundException $e) {
            return $this->create($response);
        }
    }

    /**
     * Creates user based on OAuth response
     *
     * @param UserResponseInterface $response
     * @return User
     */
    private function create(UserResponseInterface $response)
    {
        $user = new User();
        $user->setEmail($response->getEmail());
        $user->save();

        return $user;
    }
}
