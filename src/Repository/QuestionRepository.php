<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function pickRandomQuestion(array $excludedIds) : Question
    {
        $excludeList = implode(",", $excludedIds);

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Question q
            WHERE q.id NOT IN (:excludeList)'
        )->setParameter('excludeList', excludeList);
        $questions = $query->execute();

        if (!$questions) {
            return null;
        }

        $maxIndex = count($questions) - 1;
        return $questions[rand(0, $maxIndex)];
    }

}
