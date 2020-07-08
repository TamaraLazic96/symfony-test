<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\MicroPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /** @var MicroPostRepository */
    private $postRepository;

    public function __construct(MicroPostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("/{username}/followers", name="user_followers")
     * @param User $user
     * @return Response
     */
    public function userFollowers(User $user)
    {
        return new Response(
            $this->render('user/follow.html.twig', [
                'followUsers' => $user->getFollowers(),
                'user' => $user
            ])
        );
    }

    /**
     * @Route("/{username}/following", name="user_following")
     * @param User $user
     * @return Response
     */
    public function userFollowing(User $user)
    {
        return new Response(
            $this->render('user/follow.html.twig', [
                'followUsers' => $user->getFollowing(),
                'user' => $user
            ])
        );
    }

    /**
     * @Route("/{username}", name="micro_post_user")
     * @param User $user
     * @return Response
     */
    public function userPosts(User $user)
    {
        $posts = $this->postRepository->findBy(
            ['user' => $user],
            ['time' => 'DESC']
        );

        return new Response(
            $this->render('micro-post/user-posts.html.twig', [
                'posts' => $posts,
                'user' => $user
            ])
        );
    }
}