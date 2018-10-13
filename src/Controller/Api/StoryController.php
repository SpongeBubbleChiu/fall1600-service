<?php

namespace App\Controller\Api;

use App\Entity\Story;
use App\Repository\StoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("story")
 */
class StoryController extends Controller
{
    use JsonApi;

    /**
     * @Route("s", methods={"GET"})
     */
    public function index(StoryRepository $repository)
    {
        $data = $repository->findAll();
        return $this->createJsonSerializeResponse($data, array("list"));
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function show(Story $story)
    {
        return $this->createJsonSerializeResponse($story, array("detail"));
    }
}
