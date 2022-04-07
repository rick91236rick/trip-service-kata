<?php

namespace TripServiceKata\Trip;

use TripServiceKata\User\User;
use TripServiceKata\User\UserSession;
use TripServiceKata\Exception\UserNotLoggedInException;
use TripServiceKata\Trip\TripDAO;

class TripService
{

    /**
     * @var UserSession
     */
    private $userSession;

    /**
     * @var TripDAO
     */
    private $tripDAO;

    public function __construct(
        UserSession $userSession,
        TripDAO $tripDAO
    ) {
        $this->userSession = $userSession;
        $this->tripDAO = $tripDAO;
    }

    public function getTripsByUser(User $user)
    {
        $loggedUser = $this->userSession->getLoggedUser();
        $this->validateLoggedUser($loggedUser);

        return $user->isFriend($loggedUser) ? $this->tripDAO->findTripsByUser($user) : [];
    }

    public function validateLoggedUser($user) {
        if ($user == null) {
            throw new UserNotLoggedInException();
        }
    }
}
