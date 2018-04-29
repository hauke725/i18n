<?php
/**
 * Created by PhpStorm.
 * User: hauke
 * Date: 28/04/18
 * Time: 14:19
 */

namespace App\Controller;


use App\Entity\Action;
use App\Entity\TranslationKey;
use App\Entity\TranslationOccurrence;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OccurrenceController extends Controller
{

    /**
     * @Route("/occurrences", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param LoggerInterface $logger
     * @return Response
     */
    public function reportOccurrence(Request $request, EntityManagerInterface $em, LoggerInterface $logger): Response
    {
        $data = json_decode($request->getContent(), true);
        $action = $em->getRepository(Action::class)->findOneBy(['name' => $data['action']]);
        if ($action === null) {
            $action = new Action($data['action']);
            $em->persist($action);
        }
        $key = $em->getRepository(TranslationKey::class)->findOneBy(['name' => $data['key']]);
        if ($key === null) {
            $key = new TranslationKey($data['key']);
            $em->persist($key);
        }
        $occurrence = new TranslationOccurrence($action, $key);
        $em->persist($occurrence);
        try {
            $em->flush();
        } catch (ORMException|OptimisticLockException $e) {
            $logger->error($e->getMessage());
            return new Response('db failure', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new Response();
    }

    /**
     * @Route("/occurrences", methods={"GET"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse|Response
     */
    public function listTranslationOccurrences(Request $request, EntityManagerInterface $em): Response
    {
        $data = $request->query->all();
        if (array_key_exists('key', $data)) {
            $key = $em->getRepository(TranslationKey::class)->findOneBy(['name' => $data['key']]);
            if ($key === null) {
                $key = new TranslationKey($data['key']);
            }
            $occurrences = $key->getOccurrences();
        } elseif (array_key_exists('action', $data)) {
            $action = $em->getRepository(Action::class)->findOneBy(['name' => $data['action']]);
            if ($action === null) {
                $action = new Action($data['action']);
            }
            $occurrences = $action->getOccurrences();
        } else {
            return new Response('invalid request structure, need to include key or action parameter', Response::HTTP_BAD_REQUEST);
        }
        return new JsonResponse($occurrences->map(function (TranslationOccurrence $occurrence) {
            return [
                'date' => $occurrence->getCreated()->format('Y-m-d H:i:s'),
                'action' => $occurrence->getAction()->getName(),
                'key' => $occurrence->getTranslationKey()->getName(),
            ];
        })->toArray());
    }
}