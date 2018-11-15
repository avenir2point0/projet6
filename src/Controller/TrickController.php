<?php
/**
 * Created by PhpStorm.
 * User: Dimitri
 * Date: 09/11/2018
 * Time: 11:51
 */

namespace App\Controller;


use App\Entity\Trick;
use App\Form\TrickEditType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    /**
     * @var TrickRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(TrickRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/trick/view/{page<\d+>?1}/{slug}", name="trick_view")
     * @param $slug
     * @param CommentRepository $commentRepository
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function trickView($slug, CommentRepository $commentRepository, $page)
    {
        $trick = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->findTricK($slug);

        $nbPerPage = 10;

        $category = $trick->getCategory()->getName();
        $comments = $commentRepository->findCommentById($trick, $page, $nbPerPage);
        $nbPages = ceil(count($comments) / $nbPerPage);


        return $this->render('pages/trick_view.html.twig', [
            'trick' => $trick,
            'category' => $category,
            'comments' => $comments,
            'nbPages'     => $nbPages,
            'page'        => $page,
        ]);
    }

    /**
     * @Route("/trick/edit/{slug}", name="trick_details")
     * @param Trick $trick
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function trickDetails(Trick $trick, Request $request)
    {
        $form = $this->createForm(TrickEditType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Le trick à bien été modifié');
            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('pages/trick_details.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
            ]);
    }

    /**
     * @Route("/trick/new", name="trick_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createTrick(Request $request)
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($trick);
            $this->em->flush();
            $this->addFlash('success', 'Le trick à bien été ajouté');
            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('pages/trick_create.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/trick/delete/{slug}", name="trick_delete")
     * @param Trick $trick
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function trickDelete(Trick $trick, Request $request)
    {
        if ($this->isCsrfTokenValid('delete', $request->get('_token'))) {
            $this->em->remove($trick);
            $this->em->flush();
        }

        $this->addFlash('success', 'Le trick à bien été supprimé');
        return $this->redirectToRoute('app_homepage');
    }
}
