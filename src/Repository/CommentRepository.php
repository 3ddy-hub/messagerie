<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    private $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Comment::class);
    }

    public function sendMessage($data)
    {
        $comment = new Comment();
        $status  = false;
        if ($data && $data['email'] !== null && $data['message'] !== null) {
            $comment->setUser($this->security->getUser());
            $comment->setEmail($data['email']);
            $comment->setMessage($data['message']);
            $this->_em->persist($comment);
            $this->_em->flush();
        }
        return $status;
    }
}
