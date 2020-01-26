<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ItemController extends AbstractController
{
    /**
     * @Route("/item", name="item.index")
     */
    public function index(ItemRepository $itemRepository)
    {
        return $this->render('item/index.html.twig', [
            "items" => $itemRepository->findAll(),
        ]);
    }

    /**
     * @Route("/item/create", name="item.create")
     */
    public function create(Request $request, TranslatorInterface $translator)
    {
        $form = $this->createForm(ItemType::class, new Item())
                ->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($form->getData());
            $entityManager->flush();

            $this->addFlash('notice', $translator->trans("Data successfully created."));
            return $this->redirectToRoute("item.index");
        }

        return $this->render("item/create.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/item/delete/{id}", name="item.delete", methods={"POST"})
     */
    public function delete(int $id, ItemRepository $itemRepository, TranslatorInterface $translator)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($itemRepository->find($id));
        $entityManager->flush();

        $this->addFlash("notice", $translator->trans("Data successfully deleted."));
        return $this->redirectToRoute("item.index");
    }
}
