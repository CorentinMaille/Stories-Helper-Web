<?php Class Task extends Modele
{ 
    protected $rowid;
    protected $name;
    protected $description;
    // protected $MapColumn;
    protected $fk_column;
    protected $rank;
    // private $options = array();
    // protected $Author; // task author - User()
    protected $fk_user;
    protected $active;
    protected $members = array();
    protected $comments = array();

    public function __construct($rowid = null)
    {
        if($rowid != null)
        {
            $this->fetch($rowid);
        }
    }


    // SETTER

    public function setRowid($rowid)
    {
        $this->rowid = $rowid;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setFk_column($fk_column)
    {
        $this->fk_column = $fk_column;
    }

    public function setRank($rank)
    {
        $this->rank = $rank;
    }

    // public function setAuthor(User $Author)
    // {
    //     $this->Author = $Author;
    // }
    public function setFk_user(int $fk_user)
    {
        $this->fk_user = $fk_user;
    }

    public function setActive(int $active)
    {
        $this->active = $active;
    }

    
    // GETTER

    public function getRowid()
    {
        return $this->rowid;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getFk_column()
    {
        return $this->fk_column;
    }

    // public function getFk_author()
    // {
    //     return $this->Author;
    // }

    public function getFk_user()
    {
        return $this->fk_user;
    }

    public function isActive()
    {
        return intval($this->active);
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function getMembers()
    {
        return $this->members;
    }


    // CREATE

    public function create()
    {
        $sql = "SELECT MAX(rank) AS rank";
        $sql .= " FROM task";
        $sql .= " WHERE fk_column = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->fk_column]);
        $obj = $requete->fetch(PDO::FETCH_OBJ);
        $rank = $obj->rank + 1;

        $sql = "INSERT INTO task (fk_column, rank, fk_user, active)";
        $sql .= " VALUES (".$this->fk_column.",".$rank.",".$this->fk_user.",".$this->active.")";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute();
    }

    // FETCH

    public function fetch($rowid)
    {
        $sql = "SELECT t.rowid, t.name, t.description, t.fk_column, t.rank, t.fk_user, t.active";
        $sql .= " FROM task AS t";
        $sql .= " WHERE t.rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);
        
        if($requete->rowCount() > 0)
        {
            $obj = $requete->fetch(PDO::FETCH_OBJ);

            $this->rowid = $obj->rowid;
            $this->name = $obj->name;
            $this->description = $obj->description;
            // $this->MapColumn = new MapColumn($obj->fk_column);
            $this->fk_column = $obj->fk_column;
            $this->rank = $obj->rank;
            // $this->Author = new User($obj->fk_author);
            $this->fk_user = $obj->fk_user;
            $this->active = $obj->active;
            $this->fetchComments();
        }
    }

    public function fetchComments()
    {
        $sql = "SELECT tc.rowid";
        $sql .= " FROM task_comment AS tc";
        $sql .= " WHERE tc.fk_task = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);

        if($requete->rowCount() > 0)
        {
            $lines = $requete->fetchAll(PDO::FETCH_OBJ);

            foreach($lines as $line)
            {
                $this->comments[] = new TaskComment($line->rowid);
            }
        }
    }

    public function fetchMembers()
    {
        $sql = "SELECT tc.rowid, tc.fk_user";
        $sql .= " FROM task_member AS tm";
        $sql .= " WHERE tm.fk_task = ".$this->rowid;

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);

        if($requete->rowCount() > 0)
        {
            $lines = $requete->fetchAll(PDO::FETCH_OBJ);

            foreach($lines as $line)
            {
                $TaskMember = new TaskMember();
                $TaskMember->setRowid($line->rowid);
                $TaskMember->setFk_task($this->rowid);
                $TaskMember->setFk_user($line->fk_user);
                $this->members[] = $TaskMember;
            }
        }
    }

    public function fetch_last_insert_id()
    {
        $sql = "SELECT MAX(rowid) AS rowid";
        $sql .= " FROM task";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetch(PDO::FETCH_OBJ);
    }

    public function fetchRank($rowid)
    {
        $sql = "SELECT rank";
        $sql .= " FROM task";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);

        return $requete->fetch(PDO::FETCH_OBJ)->rank;
    }

    public function fetchNextRank($rowid, $fk_column)
    {
        $sql = "SELECT t.rank AS nextRank, t.rowid AS rowid";
        $sql .= " FROM task AS t";
        $sql .= " WHERE t.fk_column = ?";
        $sql .= " AND t.rank > (SELECT rank FROM task WHERE rowid = ?)";
        $sql .= " ORDER BY t.rank ASC";
        $sql .= " LIMIT 1";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_column, $rowid]);

        return $requete->fetch(PDO::FETCH_OBJ);
    }

    public function fetchPrevRank($rowid, $fk_column)
    {
        $sql = "SELECT t.rank AS prevRank, t.rowid AS rowid";
        $sql .= " FROM task AS t";
        $sql .= " WHERE t.fk_column = ?";
        $sql .= " AND t.rank < (SELECT rank FROM task WHERE rowid = ?)";
        $sql .= " ORDER BY t.rank DESC";
        $sql .= " LIMIT 1";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_column, $rowid]);

        return $requete->fetch(PDO::FETCH_OBJ);
    }


    // UPDATE

    public function update()
    {
        $sql = "UPDATE task";
        $sql .= " SET";
        $sql .= " name = '".$this->name."'";
        $sql .= " , description = '".$this->description."'";
        $sql .= " , fk_column = ".$this->fk_column;
        $sql .= " , rank = ".$this->rank;
        $sql .= " , active = ".$this->active;
        $sql .= " WHERE rowid = ".$this->rowid;

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();
    }

    // DELETE

    public function delete()
    {
        $sql = "DELETE FROM task_comment";
        $sql .= " WHERE fk_task = ".$this->rowid;

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        $sql = "DELETE FROM task_member";
        $sql .= " WHERE fk_task = ".$this->rowid;

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        $sql = "DELETE FROM task";
        $sql .= " WHERE rowid = ".$this->rowid;

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();
    }

    public function deleteByColumnId($fk_column)
    {
        $sql = "DELETE FROM task";
        $sql .= " WHERE fk_column = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$fk_column]);
    }

    
    // METHODS

    /**
     * Switch ranks between two tasks in two direction up or down
     * @param string $direction 'up' or 'down'
     */
    public function switchRank($rowid, $fk_column, $direction = 'up')
    {
        $rank = $this->fetchRank($rowid);
        if($direction == 'up')
        {
            $obj = $this->fetchNextRank($rowid, $fk_column);
            $otherRank = $obj->nextRank;
        }
        else if($direction == 'down')
        {
            $obj = $this->fetchPrevRank($rowid, $fk_column);
            $otherRank = $obj->prevRank;
        }

        $otherRowid = $obj->rowid;

        $Task = new Task($rowid);
        $Task->setRank($otherRank);
        $Task->update();

        $Task = new Task($otherRowid);
        $Task->setRank($rank);
        $Task->update();
    }

}
?>