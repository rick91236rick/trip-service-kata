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

    public function getTripsByUser(User $user) {
        $tripList = array();
        $loggedUser = $this->userSession->getLoggedUser();
        $isFriend = false;
        if ($loggedUser != null) {
            foreach ($user->getFriends() as $friend) {
                if ($friend == $loggedUser) {
                    $isFriend = true;
                    break;
                }
            }
            if ($isFriend) {
                $tripList = $this->tripDAO->findTripsByUser($user);
            }
            return $tripList;
        } else {
            throw new UserNotLoggedInException();
        }
    }
}
