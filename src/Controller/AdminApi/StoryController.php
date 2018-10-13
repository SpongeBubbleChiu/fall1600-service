<?php

namespace App\Controller\AdminApi;

use App\Entity\Story;
use App\Form\StoryType;
use App\Repository\StoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/story")
 */
class StoryController extends AbstractController
{
    /**
     * @Route("/", name="story_index", methods="GET")
     */
    public function index(StoryRepository $storyRepository): Response
    {
        return $this->render('story/index.html.twig', ['stories' => $storyRepository->findAll()]);
    }

    /**
     * @Route("/new", name="story_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $story = new Story();
        $form = $this->createForm(StoryType::class, $story);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($story);
            $em->flush();

            return $this->redirectToRoute('story_index');
        }

        return $this->render('story/new.html.twig', [
            'story' => $story,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="story_show", methods="GET")
     */
    public function show(Story $story): Response
    {
        return $this->render('story/show.html.twig', ['story' => $story]);
    }

    /**
     * @Route("/{id}/edit", name="story_edit", methods="GET|POST")
     */
    public function edit(Request $request, Story $story): Response
    {
        $form = $this->createForm(StoryType::class, $story);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('story_edit', ['id' => $story->getId()]);
        }

        return $this->render('story/edit.html.twig', [
            'story' => $story,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="story_delete", methods="DELETE")
     */
    public function delete(Request $request, Story $story): Response
    {
        if ($this->isCsrfTokenValid('delete'.$story->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($story);
            $em->flush();
        }

        return $this->redirectToRoute('story_index');
    }
}
