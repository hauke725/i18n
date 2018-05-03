<?php

namespace App\Controller;

use App\Entity\Language;
use App\Entity\TranslationFile;
use App\Entity\TranslationKey;
use App\Entity\TranslationValue;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ValueController extends Controller
{
    /**
     * A request to this route may contain multiple translations in its body:<br>
     * {"key": "value", "key2": "value2"}
     * <br>
     * These are then stored while preserving a history of eventual preexisting values for the keys.
     * Files and languages are created on the fly if they have not been referenced before.
     * @Route("/values/{fileName}/{langName}", name="addValue", methods={"POST"})
     * @param string $fileName
     * @param string $langName
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param LoggerInterface $logger
     * @return Response
     */
    public function add(string $fileName, string $langName, Request $request, EntityManagerInterface $em, LoggerInterface $logger): Response
    {
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json(['error' => 'malformed json'], Response::HTTP_BAD_REQUEST);
        }
        $file = $em->getRepository(TranslationFile::class)->findOneBy(['name' => $fileName]);
        if ($file === null) {
            $file = new TranslationFile($fileName);
            $em->persist($file);
        }
        $language = $em->getRepository(Language::class)->findOneBy(['name' => $langName]);
        if ($language === null) {
            $language = new Language($langName);
            $em->persist($language);
        }
        foreach ($data as $keyName => $value) {
            if (!is_string($keyName) || !is_string($value)) {
                return $this->json(['error' => 'translations have to be in the format key:value in an associative array'], Response::HTTP_BAD_REQUEST);
            }
            $key = $em->getRepository(TranslationKey::class)->findOneBy(['name' => $keyName]);
            if ($key === null) {
                $key = new TranslationKey($keyName);
                $em->persist($key);
            } elseif ($key->isDeleted()) {
                $key->setDeleted(false);
                $em->persist($key);
            }
            $value = new TranslationValue($key, $value, $language, $file);
            $em->persist($value);
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
     * This returns a whole translation file for the given language / file pair in json format:<br>
     * {"key": "value", "key2": "value2"}
     * <br>
     * History and occurrences may be retrieved using their respective routes.
     * @Route("/values/{file}/{langName}", name="indexValues", methods={"GET"})
     * @param string $file
     * @param string $langName
     * @param EntityManagerInterface $em
     * @param LoggerInterface $logger
     * @return Response
     */
    public function index(string $file, string $langName, EntityManagerInterface $em, LoggerInterface $logger): Response
    {
        $language = $em->getRepository(Language::class)->findOneBy(['name' => $langName]);
        if ($language === null) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }
        $file = $em->getRepository(TranslationFile::class)->findOneBy(['name' => $file]);
        if ($file === null) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }
        $values = $em->getRepository(TranslationValue::class)->findLatestByFileAndLanguage($file, $language);
        $result = [];
        /** @var TranslationValue $value */
        foreach ($values as $value) {
            $result[$value->getTranslationKey()->getName()] = $value->getValue();
        }
        return $this->json($result);
    }

    /**
     * It is possible to delete a key using this route. It will no longer show up in respective {@see index} requests.
     * It can be re-added however using {@see add}
     * @Route("/values/{fileName}/{langName}/{keyName}", name="removeValue", methods={"DELETE"})
     * @param string $fileName
     * @param string $langName
     * @param string $keyName
     * @param EntityManagerInterface $em
     * @param LoggerInterface $logger
     * @return Response
     */
    public function remove(string $fileName, string $langName, string $keyName, EntityManagerInterface $em, LoggerInterface $logger): Response
    {
        $file = $em->getRepository(TranslationFile::class)->findOneBy(['name' => $fileName]);
        if ($file === null) {
            return $this->json(['error' => 'file not found'], Response::HTTP_NOT_FOUND);
        }
        $language = $em->getRepository(Language::class)->findOneBy(['name' => $langName]);
        if ($language === null) {
            return $this->json(['error' => 'language not found'], Response::HTTP_NOT_FOUND);
        }
        $key = $em->getRepository(TranslationKey::class)->findOneBy(['name' => $keyName, 'deleted' => false]);
        if ($key === null) {
            return $this->json(['error' => 'key not found'], Response::HTTP_NOT_FOUND);
        }
        $logger->info('deleting key ' . $keyName);
        $key->setDeleted(true);
        $em->persist($key);
        $em->flush();
    }
}
