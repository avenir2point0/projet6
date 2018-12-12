<?php
/**
 * Created by PhpStorm.
 * User: Dimitri
 * Date: 12/12/2018
 * Time: 17:00
 */

namespace App\Event;


use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class CreateUserEvent extends Event
{
    const NAME = 'user.register';

    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {

        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
