<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventRequest;
use App\Entity\User;
use App\Form\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class EventController extends AbstractController
{

    #[Route('/event/new/{categoryId?}', name: 'event_new', methods: ['GET', 'POST'])]
    #[Route('/event/{id}/edit', name: 'event_edit', methods: ['GET', 'POST'])]
    #[isGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager, Event $event = null, ?int $categoryId = null): Response
    {
        $isNewEvent = !$event;
        $event = $event ?? new Event();

        if (!$isNewEvent && $event->getCreator() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can only edit your own events');
        }

        $oldImage = $event->getImage();

        $message = $isNewEvent ? 'New event created' : 'Event updated';

        $form = $this->createForm(EventType::class, $event, [
            'category_id' => $categoryId,
        ]);
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
            flash()->success($message . ' successfully!');
            return $this->redirectToRoute('event_show', ['id' => $event->getId(), 'title' => $event->getTitle()]);
        }

        return $this->render('event/new.html.twig', [
            'eventForm' => $form,
            'edit' => !$isNewEvent,
        ]);
    }

    /**
     * Delete an event
     * ex. https://localhost:8000/event/123/delete
     * @param Request $request
     * @param Event $event
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/event/{id}/delete', name: 'event_delete', methods: ['POST', 'DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $category = $event->getCategory();

        if ($event->getCreator() !== $this->getUser()) {
            $this->addFlash('error', 'You can only delete your own events');
        }
        if (!$this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid CSRF token');
            return $this->redirectToRoute('event_show', ['id' => $event->getId(), 'title' => $event->getTitle()]);
        }

        try {
            $entityManager->remove($event);
            $entityManager->flush();
            $this->addFlash('success', 'Event deleted successfully');
            return $this->redirectToRoute('category_show', ['id' => $category->getId()]);
        } catch (\Exception $e) {
            $this->addFlash('error', 'An error occurred while deleting the event');
        }

        return $this->redirectToRoute('category_show', ['id' => $category->getId()]);
    }

    /**
     * Join an event
     * ex. https://localhost:8000/event/dnb-dj-set-4564/join
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param Event $event
     * @return Response
     */
    #[Route('/event/{title}_{id}/join', name: 'event_join', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function join(Request $request, EntityManagerInterface $entityManager, Event $event): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if($user !== $event->getCreator()){

            if (!$event->getUsers()->contains($user)) {
                $event->addUser($user);

                $entityManager->persist($event);
                $entityManager->flush();
                flash()->success('You have successfully joined this event');
            } else {
                flash()->error('You are already registered for this event');
            }
        } else {
            flash()->error('You cannot join your own event');
        }

        $referer = $request->headers->get('referer');
        if ($referer) {
            return $this->redirect($referer);
        }
        return $this->redirectToRoute('event_show', ['id' => $event->getId(), 'title' => $event->getTitle()]);
    }

    /**
     * Leave an event
     * ex. https://localhost:8000/event/dnb-dj-set-4564/leave
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param Event $event
     * @return Response
     */
    #[Route('/event/{title}_{id}/leave', name: 'event_leave', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function leave(Request $request, EntityManagerInterface $entityManager, Event $event): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if($user !== $event->getCreator()){

            if ($event->getUsers()->contains($user)) {
                $event->removeUser($user);

                $entityManager->persist($event);
                $entityManager->flush();
                flash()->info('You have successfully left this event');
            } else{
                flash()->error('You are not registered for this event');
            }
        }else {
            flash()->error('You cannot leave your own event');
        }

        $referer = $request->headers->get('referer');
        if ($referer) {
            return $this->redirect($referer);
        }
        return $this->redirectToRoute('event_show', ['id' => $event->getId(), 'title' => $event->getTitle()]);
    }

    #[Route('/event/{title}_{id}/request', name: 'event_request', methods: ['GET', 'POST'])]
    public function sendRequest(Request $request, EntityManagerInterface $entityManager, Event $event): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if($user !== $event->getCreator()) {

            if (!$event->getUsers()->contains($user)) {

                if ($event->isPrivate()){
                    $eventRequest = new EventRequest();
                    $eventRequest->setEvent($event);
                    $eventRequest->setUser($user);
                    $eventRequest->setCreatedAt(new \DateTimeImmutable('now'));
                    $entityManager->persist($eventRequest);
                    $entityManager->flush();
                    flash()->success('You have successfully sent a request');
                } else {
                    flash()->error('You cannot send a request to this event');
                }
            } else{
                flash()->error('You are already registered for this event');
            }
        } else {
            flash()->error('You cannot send a request to this event');
        }

        $referer = $request->headers->get('referer');
        if ($referer) {
            return $this->redirect($referer);
        }
        return $this->redirectToRoute('event_show', ['id' => $event->getId(), 'title' => $event->getTitle()]);
    }

    #[Route('/event/{title}_{id}/cancel_request', name: 'event_cancel_request', methods: ['GET', 'POST'])]
    public function cancelRequest(Request $request, EntityManagerInterface $entityManager, Event $event): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if($user !== $event->getCreator()) {

            if (!$event->getUsers()->contains($user)) {

                if ($event->isPrivate()){

                    $eventRequest = $entityManager->getRepository(EventRequest::class)->findOneBy([
                        'event' => $event,
                        'user' => $user,
                    ]);
                    if ($eventRequest) {
                        $entityManager->remove($eventRequest);
                        $entityManager->flush();
                        flash()->info('You have successfully canceled your request');
                    } else {
                        flash()->error('No request found to cancel');
                    }
                } else {
                    flash()->error('An error occurred while canceling your request');
                }
            } else{
                flash()->error('You are not registered for this event');
            }
        } else {
            flash()->error('You cannot cancel a request to this event');
        }

        $referer = $request->headers->get('referer');
        if ($referer) {
            return $this->redirect($referer);
        }
        return $this->redirectToRoute('event_show', ['id' => $event->getId(), 'title' => $event->getTitle()]);
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
        $isJoined = $event->getUsers()->contains($this->getUser()) ?? false;
        return $this->render('event/show.html.twig', [
            'event' => $event,
            'isJoined' => $isJoined,
        ]);
    }
}
