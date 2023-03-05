<?php

namespace App\Controller;
use App\Entity\Livre;
use App\Form\LivreType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $data= $doctrine->getRepository(Livre::class)->findAll();
        return $this->render('main/index.html.twig', [
            'list' => $data
        ]);
    }
    #[Route('/create', name: 'create')]
    public function create(Request $requset,ManagerRegistry $doctrine){
        $livre = new Livre;
        $form = $this->createForm(LivreType::class,$livre);
        $form->handleRequest($requset);
        if($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $em->persist($livre);
            $em->flush();

            $this->addFlash('notice','Ajout avec Success');
            return $this->redirectToRoute('main');

        }
        return $this->render('main/create.html.twig',[
            'form' => $form->createView()
        ]);
    }
    #[Route('/update/{id}', name: 'update')]
    public function update(Request $requset,$id,ManagerRegistry $doctrine){
        $livre = $doctrine->getRepository(Livre::class)->find($id);
        $form = $this->createForm(LivreType::class,$livre);
        $form->handleRequest($requset);
        if($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $em->persist($livre);
            $em->flush();

            $this->addFlash('notice','Update avec Success');
            return $this->redirectToRoute('main');
        }
        return $this->render('main/update.html.twig',[
            'form' => $form->createView()
        ]);
    }
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Request $requset,$id,ManagerRegistry $doctrine){
        $data = $doctrine->getRepository(Livre::class)->find($id);
        $em = $doctrine->getManager();
        $em->remove($data);
        $em->flush();

        $this->addFlash('notice','Delete avec Success');
        return $this->redirectToRoute('main');

    }

}
