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
     * A request to this route should have this basic body:<br>
     * {"key": "occurrence", "key2": "occurrence2"}
     * <br>
     * The occurrence values can be anything but a url is suggested. In case of a URL the path part of the url without
     * domain and request is separately stored to be later accessed using {@see index}
     * @Route("/occurrences", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param LoggerInterface $logger
     * @return Response
     */
    public function report(Request $request, EntityManagerInterface $em, LoggerInterface $logger): Response
    {
        $data = json_decode($request->getContent(), true);

        foreach ($data as $actionName => $keys) {
            if (!is_string($actionName) || !is_array($keys)) {
                $this->json(['error' => 'occurrences have to be in the format action:keys in an associative array'], Response::HTTP_BAD_REQUEST);
            }
            $action = $em->getRepository(Action::class)->findOneBy(['name' => $actionName]);
            if ($action === null) {
                $action = new Action($actionName);
                $em->persist($action);
            }
            $tokenName = parse_url($actionName, PHP_URL_PATH);
            if ($tokenName !== false) {
                $tokenAction = $em->getRepository(Action::class)->findOneBy(['name' => $tokenName]);
                if ($tokenAction === null) {
                    $tokenAction = new Action($tokenName);
                    $em->persist($tokenAction);
                }
            } else {
                $tokenAction = null;
            }
            foreach ($keys as $keyName) {
                if (!is_string($keyName)) {
                    $this->json(['error' => 'key names array must only contain string values'], Response::HTTP_BAD_REQUEST);
                }
                $key = $em->getRepository(TranslationKey::class)->findOneBy(['name' => $keyName]);
                if ($key === null) {
                    $key = new TranslationKey($keyName);
                    $em->persist($key);
                }
                $occurrence = new TranslationOccurrence($action, $key, $tokenAction);
                $em->persist($occurrence);
            }
        }
        try {
            $em->flush();
        } catch (ORMException $e) {
            $logger->error($e->getMessage());
            return $this->json(['error' => 'db failure'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->json(['success' => true]);
    }

    /**
     * A request to this route can have three different basic forms:<br>
     * ?key=foo             lists all occurrences of a given key<br>
     * ?action=foo&strict   lists all occurrences of keys on this exact action<br>
     * ?action=foo          lists all occurrences of keys on a given action and similar actions. If the action has the
     * form of a URL then only the path part (without host or query) is used<br>
     * <br>
     * The non strict action request can be helpful to group occurrences across different environments
     * (eg stating.test.com vs www.test.com) or across different variants of the same page
     * (eg /product?id=1 vs /product?id=2)<br>
     * The routes only report the latest occurrence per key/action (depending on the route) with the date a separate key
     * @Route("/occurrences", methods={"GET"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse|Response
     */
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $data = $request->query->all();
        if (array_key_exists('key', $data)) {
            $occurrences = $this->getKeyOccurrences($em, $data);

            return $this->json(array_map(function (TranslationOccurrence $occurrence) {
                return [
                    'date' => $occurrence->getCreated()->format('Y-m-d H:i:s'),
                    'action' => $occurrence->getAction()->getName(),
                ];
            }, $occurrences));
        }

        if (array_key_exists('action', $data)) {
            $occurrences = $this->getActionOccurrences($em, $data);

            return $this->json(array_map(function (TranslationOccurrence $occurrence) {
                return [
                    'date' => $occurrence->getCreated()->format('Y-m-d H:i:s'),
                    'key' => $occurrence->getTranslationKey()->getName(),
                ];
            }, $occurrences));
        }

        return $this->json(['error' => 'invalid request structure, need to include key or action parameter'], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param EntityManagerInterface $em
     * @param $data
     * @return TranslationOccurrence[]|\Doctrine\Common\Collections\Collection
     */
    protected function getKeyOccurrences(EntityManagerInterface $em, $data): array
    {
        $key = $em->getRepository(TranslationKey::class)->findOneBy(['name' => $data['key']]);
        if ($key === null) {
            $key = new TranslationKey($data['key']);
        }
        return $em->getRepository(TranslationOccurrence::class)->findLatestByKey($key);
    }

    /**
     * @param EntityManagerInterface $em
     * @param $data
     * @return TranslationOccurrence[]|\Doctrine\Common\Collections\Collection
     */
    protected function getActionOccurrences(EntityManagerInterface $em, $data): array
    {
        $tokenName = parse_url($data['action'], PHP_URL_PATH);
        if ($tokenName === false || array_key_exists('strict', $data)) {
            $action = $em->getRepository(Action::class)->findOneBy(['name' => $data['action']]);
            if ($action === null) {
                $action = new Action($data['action']);
            }
            return $em->getRepository(TranslationOccurrence::class)->findLatestByAction($action);
        }

        $action = $em->getRepository(Action::class)->findOneBy(['name' => $tokenName]);
        if ($action === null) {
            $action = new Action($data['action']);
        }
        return $em->getRepository(TranslationOccurrence::class)->findLatestByTokenAction($action);
    }
}