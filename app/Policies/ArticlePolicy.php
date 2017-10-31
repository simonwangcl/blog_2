<?php

namespace App\Policies;

use App\Model\UserModel;
use App\Model\ArticleModel;
use App\Model\RoleModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    public function before(UserModel $user)
    {
        if($user->role_id == RoleModel::$roleAdmin){
            return true;
        }
    }

    /**
     * Determine whether the user can view the article.
     *
     * @param  \App\Model\UserModel  $user
     * @param  \App\Model\ArticleModel  $article
     * @return mixed
     */
    public function view(UserModel $user, ArticleModel $article)
    {
        //
    }

    /**
     * Determine whether the user can create articles.
     *
     * @param  \App\Model\UserModel  $user
     * @return mixed
     */
    public function create(UserModel $user)
    {
        //
    }

    /**
     * Determine whether the user can update the article.
     *
     * @param  \App\Model\UserModel  $user
     * @param  \App\Model\ArticleModel  $article
     * @return mixed
     */
    public function update(UserModel $user, ArticleModel $article)
    {
        return $user->id == $article->user_id;
    }

    /**
     * Determine whether the user can delete the article.
     *
     * @param  \App\Model\UserModel  $user
     * @param  \App\Model\ArticleModel  $article
     * @return mixed
     */
    public function delete(UserModel $user, ArticleModel $article)
    {
        return $user->id == $article->user_id;
    }
}
