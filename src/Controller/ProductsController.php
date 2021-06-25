<?php

namespace App\Controller;
use App\Entity\Product;
use App\Form\ProductType;
use App\Controller\BaseController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class ProductsController extends BaseController
{
    /**
     * @Route("/products", name="products")
     */
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);

        $products = $repository->findAll();

        return $this->render('products/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
    * @Route("/products/new", name="new_product")
    */

    public function new(Request $request, CacheInterface $cache): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->clearCache($cache);

            return $this->redirectToRoute('products');
        }

        return $this->render('products/new.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/products/edit/{id}", methods={"GET","POST"})
     */

    public function edit(Request $request, int $id, CacheInterface $cache): Response
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Product::class);

        $product = $repository->find($id);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->flush();

            $this->clearCache($cache);

            return $this->redirectToRoute('products');
        }

        return $this->render('products/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/products/delete/{id}", methods={"POST"}, name="delete_product")
     */
    public function delete(Request $request, Product $product, CacheInterface $cache): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('products');
        }

        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Product::class);

        $em->remove($product);
        $em->flush();

        $this->clearCache($cache);

        return $this->redirectToRoute('products');
    }
}
