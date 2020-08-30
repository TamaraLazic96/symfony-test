<?php

namespace App\Controller;

use App\Service\Greeting;
use App\Service\VeryBadDesign;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

// php bin/console debug:container monolog.logger
// php bin/console debug:autowiring LoggerInterface

// php bin/console debug:router

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController {

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    // @Route("/", name="blog_name")
    // @param Request $request
    /**
     * @Route("/", name="blog_index")
     * @return Response
     */
    //Request $request
    public function index() {
        // $request->get('name') -> when we have GET - example: /home?name=Tami

        return $this->render('blog/index.html.twig', [
            'posts' => $this->session->get('posts')
        ]);
    }

    /**
     * @Route("/add", name="blog_add")
     */
    public function add() {
        $posts = $this->session->get('posts');
        $posts[uniqid()] = [
            'title' => 'A random title '.rand(1, 500),
            'text' => 'Some random text nr '.rand(1, 500),
            'date' => new \DateTime(),
        ];
        $this->session->set('posts', $posts);

        return new RedirectResponse($this->generateUrl('blog_index'));
    }

    /**
     * @Route("/show/{id}", name="blog_show")
     * @param $id
     * @return Response
     */
    public function show($id) {
        $posts = $this->session->get('posts');
        if(!$posts || !isset($posts[$id])){
            throw new NotFoundHttpException('Post Not Found');
        }

        return $this->render('blog/post.html.twig', [
            'id' => $id,
            'post' => $posts[$id]
        ]);
    }
}