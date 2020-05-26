<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER')")
 * @Route("/following")
 */
class FollowingController extends AbstractController
{
    /**
     * @Route("/follow/{id}", name="following_follow")
     * @param User $user
     * @return RedirectResponse
     */
    public function follow(User $user)
    {
        /**
         * @var User $currentUser
         */
        $currentUser = $this->getUser();

        if ($user->getId() !== $currentUser->getId()) {
            // automatically will persist to the join table
            $currentUser->follow($user);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('micro_post_user',
            ['username' => $user->getUsername()]
        );
    }

    /**
     * @Route("/unfollow/{id}", name="following_unfollow")
     * @param User $user
     * @return RedirectResponse
     */
    public function unfollow(User $user)
    {
        /**
         * @var User $currentUser
         */
        $currentUser = $this->getUser();
        // delete record from the following table
        $currentUser->getFollowing()->removeElement($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('micro_post_user',
            ['username' => $user->getUsername()]
        );
    }
}