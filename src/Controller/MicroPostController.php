<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MicroPostController
 * @Route("/micro-post")
 */
class MicroPostController extends AbstractController
{

    // php bin/console debug:container 'App\Repository\MicroPostRepository'
    private $microPostRepository;
    private $entityManager;

    public function __construct(MicroPostRepository $microPostRepository, EntityManagerInterface $entityManager)
    {
        $this->microPostRepository = $microPostRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="micro_post_index")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(UserRepository $userRepository)
    {
        $currentUser = $this->getUser();

        $users = [];

        if ($currentUser instanceof User) {
            $posts = $this->microPostRepository->findAllByUsers($currentUser->getFollowing());
            $users = count($posts) === 0 ? $userRepository->findAllWithMorePosts(5, $currentUser) : [];
        } else {
            $posts = $this->microPostRepository->findAll();
        }

        $html = $this->render('micro-post/index.html.twig', [
            'posts' => $posts,
            'users' => $users
        ]);

        return new Response($html);
    }

    /**
     * @Route("/edit/{id}", name="micro_post_edit")
     * @param MicroPost $post
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(MicroPost $post, Request $request)
    {

        // use security annotation -- @Security("is_granted('edit', 'micropost'), message='some message'")
        //if(!$this->isGranted('edit', $post))
        //throw new UnauthorizedHttpException('');

        return $this->saveUpdate($post, $request);
    }

    /**
     * @Route("/delete/{id}", name="micro_post_delete")
     * @param MicroPost $post
     * @return RedirectResponse
     */
    public function delete(MicroPost $post)
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();
        // when you call flush all queries will be executed

        $this->addFlash('notice', 'Micro post was deleted!!');

        return new RedirectResponse($this->generateUrl('micro_post_index'));
    }

    /**
     * @Route("/add", name="micro_post_add")
     * @Security("is_granted('ROLE_USER')")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request)
    {

        $post = new MicroPost();
        //$post->setTime(new DateTime());
        $post->setUser($this->getUser());

        return $this->saveUpdate($post, $request);
    }

    /**
     * @Route("/user/{username}", name="micro_post_user")
     * @param User $userEntity
     * @return Response
     */
    public function userPosts(User $userEntity)
    {
        $posts = $this->microPostRepository->findBy(
            ['user' => $userEntity],
            ['time' => 'DESC']
        );

        // why this does not work
        //$posts = $userEntity->getPosts();

        $html = $this->render('micro-post/user-posts.html.twig', [
            'posts' => $posts,
            'user' => $userEntity
        ]);

        return new Response($html);
    }

    /**
     * @Route("/{id}", name="micro_post_post")
     * @param MicroPost $post
     * @return Response
     */
    public function post(MicroPost $post)
    {
        //$post = $this->microPostRepository->find($id);
        // if we pass all post we do not need this upper query

        return new Response(
            $this->render('micro-post/single.html.twig', [
                'post' => $post
            ]));
    }

    /**
     * @param MicroPost $post
     * @param Request $request
     * @return RedirectResponse|Response
     */
    private function saveUpdate(MicroPost $post, Request $request)
    {
        $form = $this->createForm(MicroPostType::class, $post);
        // validation happens here when we call handle request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($post);
            $this->entityManager->flush();
            return new RedirectResponse($this->generateUrl('micro_post_index'));
        }

        return new Response(
            $this->render('micro-post/add.html.twig', [
                'form' => $form->createView()
            ])
        );
    }
}