<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\TakenBook;
use App\Entity\TakenBooks;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/taken", name="taken.")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/{id}", name="index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $id = $request->get('id');
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        $takenBooks = $this->getDoctrine()->getRepository(TakenBooks::class)->findBy(array('user_id'=>$id));

        $books = array(); //taken books

        $notTakenBooks = $this->getDoctrine()->getRepository(Book::class)->findNotTaken();



        foreach ($takenBooks as $takenBook){
            array_push($books, $this->getDoctrine()->getRepository(Book::class)->findOneBy(array('id'=>$takenBook->getBookId())));
            foreach ($notTakenBooks as $key =>  $notTakenBook){
                if ($takenBook->getBookId() === $notTakenBook->getId()){
                    unset($notTakenBooks[$key]);
                }
            }
        }


        return $this->render('taken/index.html.twig',array(
            'user' => $user,
            'books' => $books,
            'notTakenBooks' => $notTakenBooks
        ));
    }


    /**
     * @Route("/take/{user_id}/{book_id}", name="take")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function takeBook(Request $request){
        $book_id = $request->get('book_id');
        $user_id = $request->get('user_id');


        $book = new Book();
        $book = $this->getDoctrine()->getRepository(Book::class)->find($book_id);
        $book->setActualCount($book->getActualCount()-1);

        $user = new User();
        $user = $this->getDoctrine()->getRepository(User::class)->find($user_id);

        $user->setBookCount($user->getBookCount()+1);
        $takenBook = new TakenBooks();
        $takenBook->setBookId($book_id);
        $takenBook->setUserId($user_id);




        $em = $this->getDoctrine()->getManager();
        $em->persist($takenBook);
        $em->flush();

        $this->addFlash('deleted','Kniha bola úspešne vypožičaná');
        return $this->redirect($this->generateUrl('user.index'));



    }

    /**
     * @Route("/back/{user_id}/{book_id}", name="back")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function backBook(Request $request){
        $book_id = $request->get('book_id');
        $user_id = $request->get('user_id');


        $book = new Book();
        $book = $this->getDoctrine()->getRepository(Book::class)->find($book_id);
        $book->setActualCount($book->getActualCount()+1);

        $user = new User();
        $user = $this->getDoctrine()->getRepository(User::class)->find($user_id);

        $user->setBookCount($user->getBookCount()-1);

        $takenBook = $this->getDoctrine()->getRepository(TakenBooks::class)->findOneBy(array('book_id'=>$book_id, 'user_id'=>$user_id));


        $em = $this->getDoctrine()->getManager();
        $em->remove($takenBook);
        $em->flush();

        $this->addFlash('deleted','Kniha bola úspešne vrátená');
        return $this->redirect($this->generateUrl('user.index'));



    }
}
