<?php

namespace Test\TripServiceKata\Trip;

use PHPUnit\Framework\TestCase;
use TripServiceKata\Trip\TripService;
use TripServiceKata\Exception\UserNotLoggedInException;
use TripServiceKata\User\User;
use TripServiceKata\User\UserSession;
use TripServiceKata\Trip\TripDAO;
class TripServiceTest extends TestCase
{
    /**
     * @var TripService
     */
    private $tripService;

    private $mockUserSession;

    private $mockUser;

    private $mockTripDAO;

    protected function setUp()
    {
        $this->mockUserSession = $this->createMock(UserSession::class);
        $this->mockUser = $this->createMock(User::class);
        $this->mockTripDAO = $this->createMock(TripDAO::class);
        $this->tripService = new TripService($this->mockUserSession,$this->mockTripDAO);
    }

    /** @test */
    public function should_Throw_Exception_When_User_Is_Not_LoggedIn()
    {
        
        $this->mockUserSession->method('getLoggedUser')->willReturn(null);

        $this->expectException(UserNotLoggedInException::class);
        $this->tripService->getTripsByUser( $this->mockUser);
    }

    /** @test */
    public function should_Not_Return_Trips_When_Logged_User_Are_Not_Friend()
    {
        $this->mockUserSession->method('getLoggedUser')->willReturn($this->mockUser);
        $this->mockUser->method('isFriend')->willReturn(false);

        $tripList = $this->tripService->getTripsByUser($this->mockUser);
        $this->assertCount(0, $tripList);
    }


    /** @test */
    public function should_Return_Trips_When_Logged_User_Are_Friend()
    {
        $this->mockUserSession->method('getLoggedUser')->willReturn($this->mockUser);
        $this->mockUser->method('isFriend')->willReturn(true);
        $this->mockTripDAO->method('findTripsByUser')->willReturn(["trip"]);

        $tripList = $this->tripService->getTripsByUser($this->mockUser);
        $this->assertNotCount(0,$tripList);
    }
}
