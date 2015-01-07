<?php

require_once 'Repository.php';
require_once 'TopicRepository.php';
require_once '/../Entity/User.php';
require_once '/../Entity/Topic.php';
require_once '/../Entity/Section.php';
require_once '/../Entity/Post.php';

class SectionRepository extends Repository
{
    public function findAll()
    {
        $sql = "SELECT s.id AS s_id, s.name AS s_name, s.description AS s_description, s.amount_topics AS s_amount_topics, ";
        $sql .= "t.id AS t_id, t.name AS t_name, ";
        $sql .= "p.id AS p_id, p.created_on AS p_created_on, ";
        $sql .= "u.id AS u_id, u.username AS u_username ";
        $sql .= "FROM sections s ";
        $sql .= "LEFT JOIN topics t on t.id = s.last_topic_id ";
        $sql .= "LEFT JOIN posts p on p.id = t.last_post_id ";
        $sql .= "LEFT JOIN users u on u.id = p.user_id ";

        $rows = $this->fetchAll($sql);
        
        $collection = array();
        foreach ($rows as $row) {
            $section = new Section();
            $section->setId($row['s_id']);
            $section->setName($row['s_name']);
            $section->setDescription($row['s_description']);
            $section->setAmountTopics((int) $row['s_amount_topics']);
            
            if ($row['t_id']) {
                $topic = new Topic();
                $topic->setId($row['t_id']);
                $topic->setName($row['t_name']);
                
                $section->setLastTopic($topic);
            }
            
            if ($row['p_id']) {
                $user = new User();
                $user->setId($row['u_id']);
                $user->setUsername($row['u_username']);
               
                $post = new Post();
                $post->setId($row['p_id']);
                $post->setCreatedOn(new \DateTime($row['p_created_on']));
                $post->setUser($user);
                
                $topic->setLastPost($post);
            }
            
            $collection[] = $section;
        }
        
        return $collection;
    }
    
    public function findById($sectionId)
    {
        $sql = "SELECT s.id AS s_id, s.name AS s_name, s.description AS s_description, s.amount_topics AS s_amount_topics, s.is_closed AS s_is_closed, s.created_on AS s_created_on, ";
        $sql .= "u.id AS u_id, u.username AS u_username, u.email AS u_email, u.amount_posts AS u_amount_posts, ";
        $sql .= "t.id AS t_id, t.name AS t_name ";
        $sql .= "FROM sections s ";
        $sql .= "INNER JOIN users u on s.user_id = u.id ";
        $sql .= "LEFT JOIN topics t on t.id = s.last_topic_id ";
        $sql .= "WHERE s.id = " . $this->escapeString($sectionId);

        $row = $this->fetchOne($sql);

        if (!$row) {
            return null;
        }
        
        $user = new User();
        $user->setId($row["u_id"]);
        $user->setUsername($row["u_username"]);
        $user->setEmail($row["u_email"]);
        $user->setAmountPosts($row["u_amount_posts"]);
        
        $isClosed = ($row["s_is_closed"] == "0") ? false : true;
        
        $section = new Section();
        $section->setId($row["s_id"]);
        $section->setName($row["s_name"]);
        $section->setDescription($row["s_description"]);
        $section->setAmountTopics($row["s_amount_topics"]);
        $section->setIsClosed($isClosed);
        $section->setCreatedOn(new \DateTime($row['s_created_on']));
        $section->setUser($user);
        
        if ($row['t_id']) {
            $topic = new Topic();
            $topic->setId($row['t_id']);
            $topic->setName($row['t_name']);

            $section->setLastTopic($topic);
        }
        
        return $section;
    }
    
    public function remove(Section $section)
    {
        $topicRepository = new TopicRepository();
        $topics = $topicRepository->findAllInSection($section->getId());
        
        foreach ($topics as $topic) {
            $topicRepository->delete($topic);
        }
        
        $sql = "DELETE FROM sections ";
        $sql .= "WHERE id =" .$this->escapeString($section->getId());
        
        return $this->_delete($sql);
    }
    
    public function save(Section $section)
    {
        if(!$section->getUser() || !$section->getUser()->getId()) {
            throw new \InvalidArgumentException("Cannot save section without assigned user");
        }
        
        if ($section->getId()) {
            return $this->update($section);
        } else {
            return $this->insert($section);
        }
    }
    
    private function insert(Section $section)
    {
        if ($section->isClosed()) {
            $isClosed = 1;
        } else {
            $isClosed = 0;
        }
        
        $sql = "INSERT INTO sections (`name`, `description`, `user_id`, `is_closed`) ";
        $sql .= "VALUES(";
        $sql .= "'". $this->escapeString($section->getName()) ."', ";
        $sql .= "'". $this->escapeString($section->getDescription()) ."', ";
        $sql .= $this->escapeString($section->getUser()->getId()) .", ";
        $sql .= $this->escapeString($isClosed) ." ";
        $sql .= ")";
        
        $id = $this->_insert($sql);
        $section->setId($id);
        
        return $section;
    }
    
    private function update(Section $section)
    {
        if ($section->isClosed()) {
            $isClosed = 1;
        } else {
            $isClosed = 0;
        }
        
        $sql = "UPDATE sections SET ";
        $sql .= "name='" . $this->escapeString($section->getName()) ."', ";
        $sql .= "description='" . $this->escapeString($section->getDescription()) ."', ";
        $sql .= "is_closed =" . $this->escapeString($isClosed) ." ";
        $sql .= "WHERE id =" .$this->escapeString($section->getId());
        
        return $this->_update($sql);
    }
    
    public function updateAmountTopic($sectionId, $lastTopicId, $increase = true)
    {
        $sql = "UPDATE sections SET ";
        
        if ($increase) {
            $sql .= "amount_topics = amount_topics+1 , last_topic_id = " . $this->escapeString($lastTopicId);
        } else {
            $sql .= "amount_topics = amount_topics-1 ";
            $section = $this->findById($sectionId);
            if ($section->getLastTopic() && $lastTopicId === $section->getLastTopic()->getId()) {
                $sql .= ", last_topic_id = NULL";
            }
        }

        $sql .= " WHERE id =" .$this->escapeString($sectionId);
        
        return $this->_update($sql);
    }
    
}