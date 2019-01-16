<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    use Notifiable;

    public function roles(){
        return $this->belongsToMany('App\Role');
    }

    function hasThisRole($user, $roleId) {
        foreach($user->roles as $role){
            if ($role->id == $roleId){
                return true;
            }
        };
        return false;
    }

    public function hasRole($user, $checkRole) {
        // | in checkrole parameter equals an OR statement. 2|3 means check whether user has role 2 OR 3.
        // ! before a role id means NOT. !2 passes if user does NOT have role 2.
        // & is an AND operator
        // listed logical statements can be combined without issue as long as ! prefixes numbers and &| are inbetween role numbers. 1|!2 passes if user has NOT role 2 OR has role 1.  2&!3 passes if user has not role 3 and has role 2.
        // And groups are resolved before or groups.
        foreach (explode("|", $checkRole) as $orGroups) {
            $andStatement = true;
            foreach (explode("&", $orGroups) as $andGroups){
                if ($andGroups[0] === "!") {
                    if (self::hasThisRole($user, substr($andGroups,1)) === true) {
                        $andStatement = false; break;
                    }
                } else {
                    if (self::hasThisRole($user, $andGroups) === false) {
                        $andStatement = false; break;
                    }
                }
            }
            if ($andStatement === true) {
                return true;
            }
        }
        return false;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
