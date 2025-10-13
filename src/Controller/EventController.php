<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class EventController extends AbstractController
{

    #[Route('/event/new', name: 'event_new', methods: ['GET', 'POST'])]
    #[Route('/event/{id}/edit', name: 'event_edit', methods: ['GET', 'POST'])]
    #[isGranted('ROLE_USER')]
    public function new(Event $event = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        $isNewEvent = !$event;
        $event = $event ?? new Event();

        if (!$isNewEvent && $event->getCreator() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can only edit your own events');
        }

        $oldImage = $event->getImage();

        $message = $isNewEvent ? 'New event created' : 'Event updated';

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                // Save new image first
                $newFilename = uniqid() . '.webp';
                $uploadDir = $this->getParameter('event_images_directory');
                $targetPath = $uploadDir . '/' . $newFilename;

                // Create and convert image to WebP
                $sourceImage = imagecreatefromstring(file_get_contents($imageFile));

                if ($sourceImage === false) {
                    throw new \RuntimeException('Failed to create image from uploaded file');
                }

                try {
                    $success = imagewebp($sourceImage, $targetPath);

                    if (!$success) {
                        throw new \RuntimeException('Failed to save WebP image');
                    }

                    $event->setImage($newFilename);

                    // Delete old image AFTER successful save
                    if ($oldImage && file_exists($this->getParameter('event_images_directory') . '/' . $oldImage)) {
                        unlink($this->getParameter('event_images_directory') . '/' . $oldImage);
                    }
                } finally {
                    imagedestroy($sourceImage); // Free memory
                }
            }

            if ($isNewEvent) {
                $event->setCreator($this->getUser());
            }

            $entityManager->persist($event);
            $entityManager->flush();
            $this->addFlash('success', "$message successfully");
            return $this->redirectToRoute('event_show', ['id' => $event->getId(), 'title' => $event->getTitle()]);
        }

        return $this->render('event/new.html.twig', [
            'eventForm' => $form,
            'edit' => !$isNewEvent,
        ]);
    }


    /**
     * Event's detail page
     * ex. https://localhost:8000/music/dnb-dj-set-4564
     * ex. https://localhost:8000/{param:type}/{param:titre}_{param:id}
     * @param Event $event
     * @return Response
     */
    #[Route('/event/{title}_{id}', name: 'event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', ['event' => $event]);
    }
}
