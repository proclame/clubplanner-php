<?php

namespace Proclame\Clubplanner\Models;

use Proclame\Clubplanner\Model;
use Proclame\Clubplanner\Exceptions\ClubplannerApiException;

class Member extends Model
{
    protected $endpoint = 'member/getmember';

    public function sendPasswordReset()
    {
        $this->connection()->get('Member/ForgotPassword', ['memberid' => $this->id]);
    }

    public function findByEmail(String $email)
    {
        return $this->find($email, 'email');
    }
    public function findByUid(String $uid)
    {
        return $this->find($uid, 'uid');
    }

    public function all($owner_ids = ['1'])
    {
        $members = [];
        $startFrom = 0;

        $ownerquery = $this->getOwnerQuery($owner_ids);

        do {
            $filter = $ownerquery . ' and member_id > ' . $startFrom;
            $newMembers = $this->get(['filter' => $filter]);
            $members = array_merge($members, $newMembers);
            $startFrom = end($members)->Id;
        } while (count($newMembers) === 1000);

        return $members;
    }

    public function add($attributes)
    {
        return $this->makeFromResponse(
            $this->connection()->get('Member/AddMember', $attributes)
        );
    }

    public function update($attributes)
    {
        if (isset($this->attributes['Id'])) {
            $attributes = array_merge($attributes, ['memberid' => $this->Id]);
        }
        $member = $this->makeFromResponse($this->connection()->get('Member/UpdateMember', $attributes));
        return $member;
    }

    private function getOwnerQuery($owner_ids)
    {
        $ownerquery = '( owner = \'-1\'';

        foreach ($owner_ids as $owner_id) {
            $ownerquery .= " or owner = '{$owner_id}'";
        }

        $ownerquery .= ')';
        return $ownerquery;
    }
}
