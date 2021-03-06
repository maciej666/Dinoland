<?php

namespace AddUserDinoBundle\Repository\Blog;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * CommentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentRepository extends NestedTreeRepository
{

    public function getFreshComments($post_id, $last_comment)
    {
        $t = explode(".", $last_comment);
        //Obiekt DateTime z czasem ostatniego posta na stronie
        $last_comment_date = \DateTime::createFromFormat('Y-m-d H:i:s', $t[0].'-'.$t[1].'-'.$t[2].' '.$t[3].':'.$t[4].':'.$t[5] );

        $em = $this->getEntityManager();
        $query = $em
            ->createQuery(
                'SELECT  c.createdAt, c.authorName, c.body, u.id, i.imageName '
                . 'FROM AddUserDinoBundle:Blog\Comment c INNER JOIN c.post p INNER JOIN c.user u INNER JOIN u.image i WHERE p.id =:post_id AND c.createdAt > :last_comment_date'
            )
            ->setParameter('post_id', $post_id)
            ->setParameter('last_comment_date', $last_comment_date)
        ;

        return $fresh_comments = $query->getResult();
    }


    public function getFreshCommentsTree($post_id, $last_comment)
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository('AddUserDinoBundle:Blog\Comment');
        $t = explode(".", $last_comment);
        //Obiekt DateTime z czasem ostatniego posta na stronie
        $last_comment_date = \DateTime::createFromFormat('Y-m-d H:i:s', $t[0].'-'.$t[1].'-'.$t[2].' '.$t[3].':'.$t[4].':'.$t[5] );

        $query = $em
            ->createQueryBuilder()
            ->select('c', 'u', 'i', 'p')
            ->from('AddUserDinoBundle:Blog\Comment', 'c')
            ->leftJoin('c.user', 'u')
            ->leftJoin('u.image', 'i')
            ->leftJoin('c.post', 'p')
            ->orderBy('c.createdAt', 'DESC')
            ->where('p.id = :id')
            ->andWhere('c.createdAt > :last_comment_date')
            ->setParameters(array('id' => $post_id, 'last_comment_date' => $last_comment_date))
//            ->setParameter('id', $post_id)
//            ->setParameter('last_comment_date', $last_comment_date)
            ->getQuery()
        ;
        $tree = $repo->buildTree($query->getArrayResult());
        return $tree;
    }


}
