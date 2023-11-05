<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin/image')]
class ImageController extends AbstractController
{
    #[Route('/', name: 'app_image_index', methods: ['GET'])]
    public function index(ImageRepository $imageRepository): Response
    {
        return $this->render('admin/image/index.html.twig', [
            'images' => $imageRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_image_new', methods: ['GET', 'POST'])]
    public function new(Request $request,$id, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Image::class);
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $file=$form['image']->getData();
            if($file){
                $fileName=$this->generateUniqueFileName().'.'.$file->guessExtension();
                try{
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                }catch(FileException $e){

                }
                $image->setImage($fileName);
            }
            
            $entityManager->persist($image);
            $entityManager->flush();

            


             return $this->render('admin/image/new.html.twig', [
            'image' => $image,
            'form' => $form,
            'id' => $id,
            'images'=> $repository->findBy(['hotel_id' => $id])
        ]);
        }
        

        return $this->render('admin/image/new.html.twig', [
            'image' => $image,
            'form' => $form,
            'id' => $id,
            'images'=> $repository->findBy(['hotel_id' => $id])
        ]);
    }

    #[Route('/{id}', name: 'app_image_show', methods: ['GET'])]
    public function show(Image $image): Response
    {
        return $this->render('image/show.html.twig', [
            'image' => $image,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_image_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Image $image, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/image/edit.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/{hid}', name: 'app_image_delete', methods: ['POST'])]
    public function delete(Request $request, Image $image,$hid, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            $entityManager->remove($image);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_image_new', ['id'=>$hid]);
    }

    private function generateUniqueFileName(){
        return md5(uniqid());
    }
}
