<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\User;
use App\Transformers\PostTransformer;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'posts'
    ];

    /**
     * User transformer
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'name'       => $user->name,
            'email'      => $user->email,
            'registered' => $user->created_at->diffForHumans(),
        ];
    }

    public function includePosts(User $user)
    {
        $posts = $user->posts()
                      ->latestFirst()
                      ->get();

        return $this->collection($posts, new PostTransformer());
    }
}
