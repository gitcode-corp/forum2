<?php

require_once 'Repository.php';
require_once 'SectionRepository.php';
require_once 'TopicRepository.php';
require_once 'UserRepository.php';
require_once '/../Entity/User.php';
require_once '/../Entity/Topic.php';
require_once '/../Entity/Section.php';
require_once '/../Entity/Post.php';

class PostRepository extends Repository
{
    public function removeAllInTopic(Topic $topic)
    {
        $sql = "DELETE FROM posts ";
        $sql .= "WHERE topic_id =" .$this->escapeString($topic->getId());
        
        return $this->_delete($sql);
    }
    
    public function findOne($postId, $topicId, $sectionId)
    {
        $sql = "SELECT p.id AS p_id, p.content AS p_content, p.is_edited_by_admin AS p_is_edited_by_admin, p.created_on AS p_created_on, ";
        $sql .= "t.id AS t_id, t.name AS t_name, t.description AS t_description, t.amount_posts AS t_amount_posts, t.is_closed AS t_is_closed, t.created_on AS t_created_on, ";
        $sql .= "s.id AS s_id, s.name AS s_name, s.description AS s_description, s.is_closed as s_is_closed, s.created_on AS s_created_on, ";
        $sql .= "u.id AS u_id, u.username AS u_username, u.email as u_email, u.created_on AS u_created_on ";
        $sql .= "FROM posts p ";
        $sql .= "INNER JOIN topics t on t.id = p.topic_id ";
        $sql .= "INNER JOIN sections s on s.id = t.section_id ";
        $sql .= "INNER JOIN users u on u.id = p.user_id ";
        $sql .= "WHERE p.id = " . $this->escapeString($postId) . " ";
        $sql .= "AND t.id = " . $this->escapeString($topicId) . " ";
        $sql .= "AND s.id = " . $this->escapeString($sectionId) . " ";

        $row = $this->fetchOne($sql);
        
        if (!$row) {
            return null;
        }
        
        $isClosed = ($row["s_is_closed"] == "0") ? false : true;
        $section = new Section();
        $section->setId($row["s_id"]);
        $section->setName($row["s_name"]);
        $section->setDescription($row["s_description"]);
        $section->setIsClosed($isClosed);
        $section->setCreatedOn(new \DateTime($row['s_created_on']));
        
        $isClosed = ($row['t_is_closed'] == "0") ? false : true;
        $topic = new Topic();
        $topic->setId($row['t_id']);
        $topic->setName($row['t_name']);
        $topic->setDescription($row['t_description']);
        $topic->setAmountPosts((int)$row['t_amount_posts']);
        $topic->setIsClosed($isClosed);
        $topic->setCreatedOn(new \DateTime($row['t_created_on']));
        $topic->setSection($section);
        
        $user = new User();
        $user->setId($row['u_id']);
        $user->setUsername($row['u_username']);
        $user->setEmail($row['u_email']);
        $user->setCreatedOn(new \DateTime($row['u_created_on']));
        
        $isEditedByAdmin = ($row["p_is_edited_by_admin"] == "0") ? false : true;
        $post = new Post();
        $post->setId($row['p_id']);
        $post->setContent($row['p_content']);
        $post->setCreatedOn(new \DateTime($row['p_created_on']));
        $post->setUser($user);
        $post->setIsEditedByAdmin($isEditedByAdmin);
        $post->setTopic($topic);
        
        return $post;
    }
    
    public function findAllInTopic($topicId, $sectionId)
    {
        $sql = "SELECT p.id AS p_id, p.content AS p_content, p.is_edited_by_admin AS p_is_edited_by_admin, p.created_on AS p_created_on, ";
        $sql .= "u.id AS u_id, u.username AS u_username, ";
        $sql .= "s.id AS s_id, s.is_closed AS s_is_closed, ";
        $sql .= "t.id AS t_id, t.name AS t_name, t.is_closed AS t_is_closed ";
        $sql .= "FROM posts p ";
        $sql .= "INNER JOIN topics t on t.id = p.topic_id ";
        $sql .= "INNER JOIN sections s on s.id = t.section_id ";
        $sql .= "INNER JOIN users u on u.id = p.user_id ";
        $sql .= "WHERE s.id = " . $this->escapeString($sectionId) . " ";
        $sql .= "AND t.id = " . $this->escapeString($topicId) . " ";
        $sql .= "ORDER BY p.created_on ASC";

        $rows = $this->fetchAll($sql);
        
        $collection = array();
        foreach ($rows as $row) {

            $isClosed = ($row['s_is_closed'] == "0") ? false : true;
            $section = new Section();
            $section->setId($row["s_id"]);
            $section->setIsClosed($isClosed);

            $isClosed = ($row['t_is_closed'] == "0") ? false : true;
            $topic = new Topic();
            $topic->setId($row['t_id']);
            $topic->setName($row['t_name']);
            $topic->setIsClosed($isClosed);
            $topic->setSection($section);
            
            $user = new User();
            $user->setId($row['u_id']);
            $user->setUsername($row['u_username']);
        
            $isEditedByAdmin = ($row["p_is_edited_by_admin"] == "0") ? false : true;
            $post = new Post();
            $post->setId($row['p_id']);
            $post->setContent($row["p_content"]);
            $post->setCreatedOn(new \DateTime($row['p_created_on']));
            $post->setUser($user);
            $post->setIsEditedByAdmin($isEditedByAdmin);
            $post->setTopic($topic);
            
            $collection[] = $post;
        }
        
        return $collection;
    }
    
    public function removeContent(Post $post)
    {
        if(!$post->getTopic() || !$post->getTopic()->getId()) {
            throw new \InvalidArgumentException("Cannot delete post without assigned topic");
        }
        elseif(!$post->getTopic()->getSection() || !$post->getTopic()->getSection()->getId()) {
            throw new \InvalidArgumentException("Cannot delete post without assigned section");
        }
        
        $sql = "UPDATE posts SET ";
        $sql .= "content='Post został usunięty przez admina!', ";
        $sql .= "is_edited_by_admin=1 ";
        $sql .= "WHERE id =" .$this->escapeString($post->getId());
        
        return $this->_update($sql);
    }
    
    public function save(Post $post)
    {
        if(!$post->getUser() || !$post->getUser()->getId()) {
            throw new \InvalidArgumentException("Cannot save post without assigned user");
        } elseif(!$post->getTopic() || !$post->getTopic()->getId()) {
            throw new \InvalidArgumentException("Cannot save post without assigned topic");
        }
        elseif(!$post->getTopic()->getSection() || !$post->getTopic()->getSection()->getId()) {
            throw new \InvalidArgumentException("Cannot save post without assigned section");
        }
        
        if ($post->getId()) {
            return $this->update($post);
        } else {
            return $this->insert($post);
        }
    }
    
    private function update(Post $post)
    {
        $sql = "UPDATE posts SET ";
        $sql .= "content='" . $this->escapeString($post->getContent()) ."' ";
        $sql .= "WHERE id =" .$this->escapeString($post->getId());
        
        return $this->_update($sql);
    }
    
    private function insert(Post $post)
    {
        $sql = "INSERT INTO posts (`topic_id`, `user_id`, `content`) ";
        $sql .= "VALUES(";
        $sql .= "'". $this->escapeString($post->getTopic()->getId()) ."', ";
        $sql .= "'". $this->escapeString($post->getUser()->getId()) ."', ";
        $sql .= "'". $this->escapeString($post->getContent()) ."' ";
        $sql .= ")";
        
        $id = $this->_insert($sql);
        $post->setId($id);
        
        $topicRepository = new TopicRepository();
        $topicRepository->updatePostData($post);
        
        $userRepository = new UserRepository();
        $userRepository->updateAmountPost($post->getUser());
        
        return $post;
    }
}