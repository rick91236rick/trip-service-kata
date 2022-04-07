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

    protected function setUp()
    {
        $userSession = new UserSession();
        $tripDAO = new TripDAO();
        $this->tripService = new TripService($userSession,$tripDAO);
    }

    /** @test */
    public function should_Throw_Exception_When_User_Is_Not_LoggedIn()
    {
        $user = new User("rick");
        $this->tripService->getTripsByUser($user);
        $this->expectException(UserNotLoggedInException::class);
    }

    /** @test */
    public function should_Not_Return_Trips_When_Logged_User_Are_Not_Friend()
    {
        $user = new User("rick");
        $tripList = $this->tripService->getTripsByUser($user);
        $this->assertCount(0, $tripList);
    }


    /** @test */
    public function should_Return_Trips_When_Logged_User_Are_Friend()
    {
        $user = new User("rick");
        $tripList = $this->tripService->getTripsByUser($user);
        $this->assertNotCount(0,$tripList);
    }


    // /** @test */
    // public function should_Return_Trips_When_Logged_User_Are_Friend_with_DI_tool()
    // {
    //     $this->fail('This test has not been implemented yet.');
    // }

    // public function createNotLoggedInUser(): void
    // {
    //     $mockCCP = $this->getMockBuilder(UserSession::class)
    //         ->setMethods(['chargeCreditCard'])
    //         ->getMock();

    //     $mockCCP
    //         ->method('chargeCreditCard')
    //         ->willReturn(true);
    // }
}
