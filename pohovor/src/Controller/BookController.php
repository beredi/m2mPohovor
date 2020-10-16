<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Form\BookPlusType;
use App\Form\BookType;
use App\Form\UserType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/book", name="book.")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param BookRepository $bookRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(BookRepository $bookRepository)
    {
        $books = $bookRepository->findAll();
        return $this->render('book/index.html.twig', [
            'books' => $books
        ]);
    }

    /**
     * @Route("/add", name="add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addBook(Request $request)
    {
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $book->setActualCount($book->getCount());

            $em->persist($book);
            $em->flush();

            $this->addFlash('deleted', 'Kniha bol úspešne pridaná');

            return $this->redirect($this->generateUrl('book.index'));
        }

        return $this->render('book/add.html.twig',[
            'form' => $form->createView()
        ]);


    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @param Book $book
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function removeBook(Book $book){
        $em = $this->getDoctrine()->getManager();
        $em->remove($book);
        $em->flush();

        $this->addFlash('deleted','Kniha bola úspešne vymazaná');
        return $this->redirect($this->generateUrl('book.index'));
    }

    /**
     * @Route("/add/{id}", name="addmore")
     * @param Request $request
     */

    public function addSpecificBook(Request $request, $id){
        $book = new Book();
        $book = $this->getDoctrine()->getRepository(Book::class)->find($id);
        $oldCount = $book->getCount();

        $form = $this->createForm(BookPlusType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $newCount = $book->getCount();
            $book->setActualCount($book->getActualCount()+$newCount);
            $book->setCount($oldCount+$newCount);
            $em->flush();

            return $this->redirect($this->generateUrl('book.index'));
        }

        return $this->render('book/add2.html.twig',[
            'form' => $form->createView()
        ]);

    }
}