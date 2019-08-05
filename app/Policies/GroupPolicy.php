<?php

namespace App\Policies;

use App\Group;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
    * Create a new policy instance.
    *
    * @return void
    */
    public function __construct()
    {
        //
    }

    // a super admin can do everything
    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
    * Viewing a group means reading title and presentation (= group home page).
    * Only secret groups are hidden from non members
    */
    public function view(?User $user, Group $group)
    {
        if ($group->isSecret()) {
            if ($user->isMemberOf($group)) {
                return true;
            }
            else {
                return false;
            }
        }

        return true;
    }

    public function create(User $user)
    {
        if (setting('user_can_create_groups') == true) {
            return true;
        } else {
            return false;
        }
    }

    public function delete(User $user, Group $group)
    {
        return $user->isAdminOf($group);
    }

    /**
    * Determine if the given group can be updated by the user.
    *
    * @param \App\User $user
    *
    * @return bool
    */
    public function update(User $user, Group $group)
    {
        return $user->isAdminOf($group);
    }

    /**
    *   Can the user administer the group or not?
    */
    public function administer(User $user, Group $group)
    {
        return $user->isAdminOf($group);
    }

    /*
    the following functions let us decide if a user can or cannot creat some stuff in a group
    Curently it's based on the fact that you are an active member of the group OR we use the admin defined permissions
    The function getPermissionsFor() is defined in the base class BasePolicy::getPermissionsFor()
    */

    public function createDiscussion(User $user, Group $group)
    {
        return $this->getPermissionsFor($user, $group)->contains('create-discussion');
    }

    public function createFile(User $user, Group $group)
    {
        return $this->getPermissionsFor($user, $group)->contains('create-file');
    }

    public function createLink(User $user, Group $group)
    {
        return $this->getPermissionsFor($user, $group)->contains('create-file');
    }

    public function createAction(User $user, Group $group)
    {
        return $this->getPermissionsFor($user, $group)->contains('create-action');
    }

    public function createComment(User $user, Group $group)
    {
        return $this->getPermissionsFor($user, $group)->contains('create-discussion');
    }

    public function viewDiscussions(?User $user, Group $group)
    {
        // isn't it lovely :
        if ($user) {
            return $group->isOpen() || $user->isMemberOf($group);
        }
    }

    public function viewActions(?User $user, Group $group)
    {
        if ($user) {
            return $group->isOpen() || $user->isMemberOf($group);
        }
    }

    public function viewMembers(?User $user, Group $group)
    {
        if ($user) {
            return $user->isMemberOf($group);
        }
    }

    public function viewFiles(?User $user, Group $group)
    {
        if ($user) {
            return $group->isOpen() || $user->isMemberOf($group);
        }
    }

    public function viewTags(?User $user, Group $group)
    {
        if ($user) {
            return $group->isOpen() || $user->isMemberOf($group);
        }
    }

    public function manageTags(?User $user, Group $group)
    {
        if ($user) {
            return $user->isAdminOf($group);
        }
    }

    public function changeGroupType(User $user, Group $group)
    {
        return $user->isAdminOf($group);
    }

    public function invite(User $user, Group $group)
    {
        if ($group->getSetting('custom_permissions')){
            $permissions = $group->getSetting('permissions');
            $member = collect($permissions['member']);
            return $member->contains('invite');
        }


        return $user->isMemberOf($group);
    }

    public function history(User $user, Group $group)
    {
        return $user->isMemberOf($group);
    }

    public function manageMembership(User $user, Group $group)
    {
        return $user->isAdminOf($group);
    }

    public function join(User $user, Group $group)
    {
        // if group is open anyone can join, else it's invite only
        if ($group->group_type == $group::OPEN) {
            return true;
        } elseif ($group->group_type == $group::CLOSED) {
            // do we have an invite already for this group and user?
            $invite = \App\Invite::where('email', $user->email)->where('group_id', $group->id)->count();
            if ($invite == 1) {
                return true;
            }
        }

        return false;
    }
}
