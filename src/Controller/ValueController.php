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
}
