<?php

namespace App\Controller;
use App\Entity\Discount;
use App\Form\DiscountType;
use App\Controller\BaseController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class DiscountsController extends BaseController
{
    /**
     * @Route("/discounts", name="discounts")
     */
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Discount::class);

        $discounts = $repository->findAll();


        return $this->render('discounts/index.html.twig', [
            'discounts' => $discounts
        ]);
    }

    /**
     * @Route("/discounts/new", name="new_discount")
     */

    public function new(Request $request, CacheInterface $cache): Response
    {
        $discount = new Discount();
        $form = $this->createForm(DiscountType::class, $discount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($discount);
            $em->flush();

            $this->clearCache($cache);

            return $this->redirectToRoute('discounts');
        }

        return $this->render('discounts/new.html.twig', [
            'discount' => $discount,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/discounts/edit/{id}", methods={"GET","POST"})
     */


    public function edit(Request $request, int $id, CacheInterface $cache): Response
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Discount::class);

        $discount = $repository->find($id);
        $form = $this->createForm(DiscountType::class, $discount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->flush();

            $this->clearCache($cache);

            return $this->redirectToRoute('discounts');
        }

        return $this->render('discounts/edit.html.twig', [
            'discount' => $discount,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/discounts/delete/{id}", methods={"POST"}, name="delete_discount")
     */
    public function delete(Request $request, Discount $discount, CacheInterface $cache): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('discounts');
        }

        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Discount::class);

        $em->remove($discount);
        $em->flush();

        $this->clearCache($cache);

        return $this->redirectToRoute('discounts');
    }
}
