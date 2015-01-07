<?php

require_once 'Repository.php';
require_once 'SectionRepository.php';
require_once 'PostRepository.php';
require_once '/../Entity/User.php';
require_once '/../Entity/Topic.php';
require_once '/../Entity/Section.php';
require_once '/../Entity/Post.php';

class TopicRepository extends Repository
{
    public function findAllInSection($sectionId)
    {
        $sql = "SELECT t.id AS t_id, t.name AS t_name, t.amount_posts AS t_amount_posts, t.created_on AS t_created_on, t.is_closed AS t_is_closed, ";
        $sql .= "s.id AS s_id, s.is_closed AS s_is_closed ";
        $sql .= "FROM topics t ";
        $sql .= "INNER JOIN sections s on s.id = t.section_id ";
        $sql .= "WHERE s.id = " . $this->escapeString($sectionId) . " ";

        $rows = $this->fetchAll($sql);
        
        $collection = array();
        foreach ($rows as $row) {
            $isClosed = ($row['s_is_closed'] == "0") ? false : true;
            $section = new Section();
            $section->setId($row['s_id']);
            $section->setIsClosed($isClosed);
            
            $isClosed = ($row['t_is_closed'] == "0") ? false : true;
            $topic = new Topic();
            $topic->setId($row['t_id']);
            $topic->setName($row['t_name']);
            $topic->setAmountPosts((int) $row['t_amount_posts']);
            $topic->setCreatedOn(new \DateTime($row['t_created_on']));
            $topic->setIsClosed($isClosed);
            $topic->setSection($section);
            
            $collection[] = $topic;
            
        }
        
        return $collection;
    }
    
    public function findAllInSectionWithLastPost($sectionId)
    {
        $sql = "SELECT t.id AS t_id, t.name AS t_name, t.description AS t_description, t.amount_posts AS t_amount_posts, t.created_on AS t_created_on, ";
        $sql .= "p.id AS p_id, p.created_on AS p_created_on, ";
        $sql .= "u.id AS u_id, u.username AS u_username, ";
        $sql .= "ut.id AS ut_id, ut.username AS ut_username, ";
        $sql .= "s.id AS s_id ";
        $sql .= "FROM topics t ";
        $sql .= "INNER JOIN sections s on s.id = t.section_id ";
        $sql .= "INNER JOIN users ut on ut.id = t.user_id ";
        $sql .= "LEFT JOIN posts p on p.id = t.last_post_id ";
        $sql .= "LEFT JOIN users u on u.id = p.user_id ";
        $sql .= "WHERE s.id = " . $this->escapeString($sectionId) . " ";
        $sql .= "ORDER BY t.created_on DESC";

        $rows = $this->fetchAll($sql);
        
        $collection = array();
        foreach ($rows as $row) {
            $section = new Section();
            $section->setId($row['s_id']);
            
            $user = new User();
            $user->setId($row['ut_id']);
            $user->setUsername($row['ut_username']);
            
            $topic = new Topic();
            $topic->setId($row['t_id']);
            $topic->setName($row['t_name']);
            $topic->setAmountPosts((int) $row['t_amount_posts']);
            $topic->setCreatedOn(new \DateTime($row['t_created_on']));
            $topic->setSection($section);
            $topic->setUser($user);
            
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
            
            $collection[] = $topic;
            
        }
        
        return $collection;
    }
    
    public function findById($topicId, $sectionId)
    {
        $sql = "SELECT t.id AS t_id, t.name AS t_name, t.description AS t_description, t.amount_posts AS t_amount_posts, t.is_closed AS t_is_closed, t.created_on AS t_created_on, ";
        $sql .= "s.id AS s_id, s.name AS s_name, s.description AS s_description, s.is_closed as s_is_closed, s.created_on AS s_created_on, ";
        $sql .= "u.id AS u_id, u.username AS u_username, u.email as u_email, u.created_on AS u_created_on ";
        $sql .= "FROM topics t ";
        $sql .= "INNER JOIN sections s on s.id = t.section_id ";
        $sql .= "INNER JOIN users u on u.id = t.user_id ";
        $sql .= "WHERE t.id = " . $this->escapeString($topicId) . " ";
        $sql .= "AND s.id = " . $this->escapeString($sectionId) . " ";

        $row = $this->fetchOne($sql);
        
        if (!$row) {
            return null;
        }
        
        $isClosed = ($row['s_is_closed'] == "0") ? false : true;
        $section = new Section();
        $section->setId($row['s_id']);
        $section->setName($row['s_name']);
        $section->setDescription($row['s_description']);
        $section->setIsClosed($isClosed);
        $section->setCreatedOn(new \DateTime($row['s_created_on']));
        
        $user = new User();
        $user->setId($row['u_id']);
        $user->setUsername($row['u_username']);
        $user->setEmail($row['u_email']);
        $user->setCreatedOn(new \DateTime($row['u_created_on']));
        
        $isClosed = ($row['t_is_closed'] == "0") ? false : true;
        $topic = new Topic();
        $topic->setId($row['t_id']);
        $topic->setName($row['t_name']);
        $topic->setDescription($row['t_description']);
        $topic->setAmountPosts((int)$row['t_amount_posts']);
        $topic->setIsClosed($isClosed);
        $topic->setCreatedOn(new \DateTime($row['t_created_on']));
        $topic->setUser($user);
        $topic->setSection($section);
        
        return $topic;
        
    }
    
    public function save(Topic $topic)
    {
        if(!$topic->getUser() || !$topic->getUser()->getId()) {
            throw new \InvalidArgumentException("Cannot save topic without assigned user");
        } elseif(!$topic->getSection() || !$topic->getSection()->getId()) {
            throw new \InvalidArgumentException("Cannot save topic without assigned section");
        }
        
        if ($topic->getId()) {
            $this->update($topic);
        } else {
            return $this->insert($topic);
        }
    }
    
    private function insert(Topic $topic)
    {
        $isClosed = ($topic->isClosed()) ? 1 : 0;
        $sql = "INSERT INTO topics (`name`, `description`, `user_id`, `section_id`, `is_closed`) ";
        $sql .= "VALUES(";
        $sql .= "'". $this->escapeString($topic->getName()) ."', ";
        $sql .= "'". $this->escapeString($topic->getDescription()) ."', ";
        $sql .= $this->escapeString($topic->getUser()->getId()) .", ";
        $sql .= $this->escapeString($topic->getSection()->getId()) .", ";
        $sql .= $this->escapeString($isClosed) ." ";
        $sql .= ")";
        
        $id = $this->_insert($sql);
        $topic->setId($id);
        
        $sectionRepository = new SectionRepository();
        $sectionRepository->updateAmountTopic($topic->getSection()->getId(), $topic->getId());
        
        $section = $topic->getSection();
        $section->setLastTopic($topic);
        $sectionRepository->save($section);
        
        return $topic;
    }
    
    private function update(Topic $topic)
    {
        if ($topic->isClosed()) {
            $isClosed = 1;
        } else {
            $isClosed = 0;
        }
        
        $sql = "UPDATE topics SET ";
        $sql .= "name='" . $this->escapeString($topic->getName()) ."', ";
        $sql .= "description='" . $this->escapeString($topic->getDescription()) ."', ";
        $sql .= "is_closed =" . $this->escapeString($isClosed) ." ";
        $sql .= "WHERE id =" .$this->escapeString($topic->getId());
        
        return $this->_update($sql);
    }
    
    public function updatePostData(Post $post)
    {
        $sql = "UPDATE topics SET ";
        $sql .= "last_post_id='" . $this->escapeString($post->getId()) ."', ";
        $sql .= "amount_posts=amount_posts+1 ";
        $sql .= "WHERE id =" .$this->escapeString($post->getTopic()->getId());
        
        return $this->_update($sql);
    }
    
    public function delete(Topic $topic)
    {
        if(!$topic->getSection() || !$topic->getSection()->getId()) {
            throw new \InvalidArgumentException("Cannot delete topic without assigned section");
        }
        
        $postRepository = new PostRepository();
        $postRepository->removeAllInTopic($topic);
        
        $sectionRepository = new SectionRepository();
        $sectionRepository->updateAmountTopic($topic->getSection()->getId(), $topic->getId(), false);    
        
        $sql = "DELETE FROM topics ";
        $sql .= "WHERE id =" .$this->escapeString($topic->getId());
        
        return $this->_delete($sql);
    }
}