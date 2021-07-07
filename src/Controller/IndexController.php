<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 * @package App\Controller
 */
class IndexController extends AbstractController
{
    private $comment_repository;

    /**
     * IndexController constructor.
     * @param CommentRepository $comment_repository
     */
    public function __construct(CommentRepository $comment_repository)
    {
        $this->comment_repository = $comment_repository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $comment = new Comment();
        $form    = $this->createForm(CommentType::class, $comment);

        return $this->render('index/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/post-comment", name="post_comment")
     */
    public function postMessage(Request $request)
    {
        $datas = $request->query->all();

        $status = $this->comment_repository->sendMessage($datas);

        return new JsonResponse([
            'status' => $status
        ]);
    }

    /**
     * @Route("/comment-list-ajax", name="comment_list_ajax")
     */
    public function commentListAjax()
    {
        $datas    = [];
        $results  = [];
        $comments = $this->comment_repository->findBy([], ['id' => 'ASC']);
        foreach ($comments as $comment) {
            $datas['username'] = $comment && $comment->getUser() ? $comment->getUser()->getUsername() : '';
            $datas['email']    = $comment && $comment->getEmail() ? $comment->getEmail() : '';
            $datas['date_add'] = $comment && $comment->getDateAdd() ? $comment->getDateAdd() : '';
            $datas['message']  = $comment && $comment->getMessage() ? $comment->getMessage() : '';
            array_push($results, $datas);
        }

        return $this->json($results);
    }
}
