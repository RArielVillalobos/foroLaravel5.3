<?php

namespace App\Policies;

use App\Comment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

   public function accept(User $user,Comment $comment){
       //si el usuario es igual al usuario que creo el post

        return $user->owns($comment->post);

   }
}
