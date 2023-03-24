<?php
require_once('AbstractRepository.php');
require_once('UserRepository.php');

class CommentRepository extends AbstractRepository
{
    /**
     * @return Comment[]
     */
    public function getComments(): array
    {
        $sql = "SELECT * FROM commentaire";
        $statement = $this->db->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_CLASS, Comment::class);

    }

    /**
     * @param int $articleId
     *
     * @return Comment[]
     */

    public function findCommentByArticle(int $articleId): array
    {
        $sql = "SELECT * FROM commentaire WHERE articleId = :articleId";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            'articleId' => $articleId
        ]);

        return @$statement->fetchAll(PDO::FETCH_CLASS, Comment::class);
    }


    /**
     * @param int $userId
     *
     * @return Comment|bool
     */
    public function findCommentByUser(int $userId): Comment|bool
    {
        $sql = "SELECT * FROM commentaire WHERE userId = :id";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            'id' => $userId
        ]);

        return $statement->fetchObject(Comment::class);
    }


    public function deleteComment(int $commentId): void
    {
        $sql = "DELETE FROM commentaire WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([
            'id' => $commentId
        ]);
    }

    public function editComment(Comment $comment)
    {
        $sql = "UPDATE commentaire SET commentaire = :comment 
               WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([
            'id' => $comment->getId(),
            'comment' => $comment->getComment()

        ]);
    }

    public function addComment(Comment $comment)
    {
        $sql = "INSERT INTO commentaire (commentaire,userId,articleId)
            VALUES (:commentaire,:userId,:articleId)";
        $query = $this->db->prepare($sql);
        $query->execute([
            'commentaire' => $comment->GetComment(),
            'userId' => $comment->GetUserId(),
            'articleId' => $comment->GetArticleId()
        ]);
    }

    /**
     * @param int $commentId
     * @return User|bool
     */
    public function findUserComment(int $commentId): User|bool
    {
        $sql = "SELECT * FROM user
        JOIN commentaire on commentaire.userId = user.id
        WHERE commentaire.id = :commentId ";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            'commentId' => $commentId
        ]);

        return $statement->fetchObject(User::class);

    }

}