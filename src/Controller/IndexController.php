<?php


namespace App\Controller;


use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RequestContext;

//use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    private $dataService;
    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }
    public function home()
    {
        return $this->render('index.html.twig');
        /*return new Response(
            '<h1>Hello Symfony</h1>'
        );*/
        //return $this->json(['data' => 'here]);
    }

    public function newPostPage()
    {
        return $this->render('newPost.html.twig');
    }

    public function readPost($id)
    {
        if (!is_numeric($id))
        {
            return $this->home();
        }
        $result = $this->dataService->readPost($id);
        /*if (mysqli_num_rows($result) == 0) {
            //post not found
            return $this->render('error.html.twig',
                ['message' => "Could not find post #$id"]);
        }
        $post = $result->fetch_array(MYSQLI_ASSOC);
        echo(" --- " . print_r($this->readComments($id)));*/
        if (count($result)==1) {
            $post = $result[0];

            //read the comments for this post

            $comments = $this->dataService->readComments($id);
            return $this->render('readPost.html.twig',
                ['id' => $id, 'title' => $post['title'], 'body' => $post['body'],
                    'time' => $post['time'], 'userid' => $post['userid'], 'username' => $post['username'], 'comments' => $comments]);
        }
        else {
            return $this->home();
        }
    }

    public function readComments($postid)
    {
        $result = $this->dataService->readComments($postid);
        if (count($result)==0)
        {
            return "This post has no comments yet.";
        }
        return $result;
        /*if (mysqli_num_rows($result) == 0) {
            //comments not found
            return "This post has no comments yet.";
        }
        $comments = $result->fetch_array(MYSQLI_ASSOC);
        return $comments;*/
    }

    public function sendPost(Request $request)
    {
        $title = $request->request->get('title');
        $body = $request->request->get('body');
        $this->dataService->newPost($title, $body);
        return $this->render('index.html.twig');
    }

    public function sendComment(Request $request)
    {
        $title = $request->request->get('title');
        $body = $request->request->get('body');
        $postid = $request->request->get('postid');
        if (is_numeric($postid)) {
            $this->dataService->leaveComment($postid, $title, $body);
            return $this->render('index.html.twig');
        }
        return $this->render('index.html.twig');
    }
}