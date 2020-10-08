<?php

namespace Proclame\Clubplanner\Test;

use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Faker\Provider\nl_BE\Person;
use Proclame\Clubplanner\Connection;
use Proclame\Clubplanner\Clubplanner;
use Proclame\Clubplanner\Models\Member;
use Proclame\Clubplanner\Exceptions\ClubplannerException;

/**
 * @runTestsInSeparateProcesses
 */
class ClubplannerMemberTest extends TestCase
{
    protected $connection;
    protected $clubplanner;

    protected $faker;

    protected function setUp(): void
    {
        $this->connection = new Connection();
        $this->connection->setApiKey($_ENV['CLUBPLANNER_TOKEN']);
        $this->connection->setApiUrl($_ENV['CLUBPLANNER_URL']);
        $this->connection->connect();
        $this->faker = Factory::create('nl_BE');
        $this->clubplanner = new Clubplanner($this->connection);
    }

    public function testFindMemberReturnsCorrectMember()
    {
        $member = $this->clubplanner->member()->find(233);
        $this->assertInstanceOf(Member::class, $member);
        $this->assertEquals('233', $member->Id);
    }

    public function testFindNotExistingMemberReturnsNull()
    {
        $member = $this->clubplanner->member()->find(234567890);
        $this->assertNull($member);
    }

    public function testFindMemberByEmailReturnsCorrectMember()
    {
        $member = $this->clubplanner->member()->findByEmail('info@clubplanner.be');
        $this->assertInstanceOf(Member::class, $member);
        $this->assertEquals('info@clubplanner.be', $member->EmailAddress);
    }

    public function testFindNotExistingMemberByEmailReturnsNull()
    {
        $member = $this->clubplanner->member()->findByEmail('abc@abcde.be');
        $this->assertNull($member);
    }
    public function testFindMemberByUidReturnsCorrectMember()
    {
        $member = $this->clubplanner->member()->findByUid('12');
        $this->assertInstanceOf(Member::class, $member);
        $this->assertEquals('12', $member->UId);
    }

    public function testFindNotExistingMemberByUidReturnsNull()
    {
        $member = $this->clubplanner->member()->findByUid('abc@abcdef.be');
        $this->assertNull($member);
    }

    public function testFindAllMembersReturnsArrayOfAllMembers()
    {
        $members = $this->clubplanner->member()->all([1,2,3]);
        $this->assertIsArray($members);
        $this->assertGreaterThan(1000, count($members));
        $this->assertInstanceOf(Member::class, $members[5]);
    }

    public function testGetMembersByFilterReturnsArrayOfCorrectMembers()
    {
        $members= $this->clubplanner->member()->get(['filter' => "Email_Address = 'info@clubplanner.be'"]);
        $this->assertIsArray($members);
        $this->assertInstanceOf(Member::class, $members[0]);
    }

    public function testGetMembersByUnexistingFilterReturnsEmptyArray()
    {
        $members= $this->clubplanner->member()->get(['filter' => "Email_Address = '123@onetwothree.four'"]);
        $this->assertIsArray($members);
        $this->assertCount(0, $members);
    }

    public function testAddMemberAddsTheMember()
    {
        $email = $this->faker->email;
        $firstname = $this->faker->firstname;
        $lastname = $this->faker->lastname;

        $member = $this->clubplanner->member()->add([
            'email' => $email,
            'firstname' => $firstname,
            'lastname' => $lastname,
        ]);

        $this->assertInstanceOf(Member::class, $member);
        $this->assertIsInt($member->Id);
        $this->assertGreaterThan(100, $member->Id);
        $this->assertEquals($firstname . ' ' . $lastname, $member->FullName);
    }

    public function testUpdateMemberUpdatesTheMember()
    {
        $email = $this->faker->email;
        $firstname = $this->faker->firstname;
        $lastname = $this->faker->lastname;

        $member = $this->clubplanner->member()->add([
            'email' => $email,
            'firstname' => $firstname,
            'lastname' => $lastname,
        ]);
        $memberId = $member->Id;

        $newEmail = $this->faker->email;
        $newFirstname = $this->faker->firstname;
        $newLastname = $this->faker->lastname;

        $member = $member->update([
            'email' => $newEmail,
            'firstname' => $newFirstname,
            'lastname' => $newLastname,
        ]);

        $member = $this->clubplanner->member()->find($memberId);

        $this->assertInstanceOf(Member::class, $member);
        $this->assertIsInt($member->Id);
        $this->assertEquals($memberId, $member->Id);
        $this->assertEquals($newEmail, $member->EmailAddress);
    }
}
