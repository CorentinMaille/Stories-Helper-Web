<?php
class User extends Modele
{
    protected $rowid;
    protected $firstname;
    protected $lastname;
    protected $birth;
    protected $password;
    protected $email;
    // private $Organization;
    protected $fk_organization;
    protected $BelongsTo;
    protected $consent;
    protected $admin;

    public function __construct($rowid = null)
    {
        if($rowid != null)
        {
            $this->fetch($rowid);
        }
    }

    // SETTER

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function setBirth($birth)
    {
        $this->birth = $birth;
    }

    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    // public function setOrganization(Organization $Organization)
    // {
    //     $this->Organization = $Organization;
    // }
    public function setFk_organization(int $fk_organization)
    {
        $this->fk_organization = $fk_organization;
    }

    public function setConsent(bool $consent)
    {
        $this->consent = $consent;
    }

    public function setAdmin(int $admin)
    {
        $this->admin = $admin;
    }


    // GETTER

    public function getRowid()
    {
        return $this->rowid;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function getBirth()
    {
        return $this->birth;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    // public function getOrganization()
    // {
    //     return $this->Organization;
    // }
    public function getFk_organization()
    {
        return $this->fk_organization;
    }

    public function getConsent()
    {
        return $this->consent;
    }

    public function isAdmin()
    {
        return $this->admin;
    }

    public function getBelongsTo()
    {
        return $this->BelongsTo;
    }
    
    // METHODES

    /** Check if an email is linked to a user
     * @param string $email the email to check
     * @return boolean true if email is linked to a user, otherwise not linked
     */
    public function checkByEmail(string $email)
    {
        $sql = "SELECT u.email";
        $sql .= " FROM user AS u"; 
        $sql .= " WHERE u.email = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$email]);

        if($requete->rowcount() > 0)
        {
            return true;
        } 
        else 
        {
            return false;
        }
    }
    
    public function checkToken($idUser, $token)
    {
        $sql = "SELECT *";
        $sql .= " FROM user";
        $sql .= " WHERE rowid = ?";
        $sql .= " AND token = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idUser, $token]);

        if($requete->rowcount() > 0)
        {
            return true;
        } 
        else 
        {
            return false;
        }
    }

    // FETCH

    public function fetch(int $rowid)
    {
        $sql = "SELECT *"; 
        $sql .= " FROM user"; 
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);

        if($requete->rowCount() > 0)
        {
            $obj = $requete->fetch(PDO::FETCH_OBJ);

            $this->rowid = intval($rowid);
            $this->lastname = $obj->lastname;
            $this->firstname = $obj->firstname;
            $this->birth = $obj->birth;
            $this->password = $obj->password;
            $this->email = $obj->email;
            // $this->Organization = new Organization($obj->fk_organization);
            $this->fk_organization;
            $this->consent = $obj->consent;
            $this->admin = $obj->admin;

            $BelongsTo = new BelongsTo();

            $this->BelongsTo = $BelongsTo->fetchAll($obj->rowid);
        }
    }

    public function fetch_last_insert_id()
    {
        $sql = "SELECT MAX(rowid) as rowid";
        $sql .= " FROM user";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        if($requete->rowCount() > 0)
        {
            return $requete->fetch(PDO::FETCH_OBJ)->rowid;
        }
    }

    /** Return users that are not related to a project
     * @param int $projectId The project for which we are looking for unrelated users
     * @param int $idOrganization The organization in which we will search for users.
     * @return List<User>|false unrelated users if any, otherwise false
     */
    public function fetchFreeUsersByProjectId(int $projectId, int $idOrganization)
    {
        $sql = "SELECT u.rowid";
        $sql .= " FROM user AS u";
        $sql .= " LEFT JOIN belong_to AS b ON u.rowid = b.fk_user";
        $sql .= " LEFT JOIN teams AS t ON b.fk_team = t.rowid";
        $sql .= " WHERE t.fk_project = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$projectId]);

        $lines = $requete->fetchAll(PDO::FETCH_OBJ);

        $notFreeUsers = array();
        foreach($lines as $line)
        {
            $notFreeUsers[] = $line->rowid;
        }

        $notFreeUsers = implode("', '", $notFreeUsers);
        
        $sql = "SELECT u.rowid, u.lastname, u.firstname, u.birth, u.password, u.email, u.fk_organization";
        $sql .= " FROM user AS u";
        $sql .= " WHERE u.rowid NOT IN(";
        $sql .= " SELECT rowid";
        $sql .= " FROM user";

        $sql .= " WHERE rowid IN ('".$notFreeUsers."') )"; 
        $sql .= " AND u.fk_organization = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idOrganization]);        
        
        return $requete->fetchAll(PDO::FETCH_OBJ);
    }

    //* outdated, use instead $Project->getTeams()->getUsers()
    public function fetchByTeam($idTeam)
    {
        $sql = "SELECT u.rowid, u.lastname, u.firstname, u.birth, u.password, u.email, u.fk_organization"; 
        $sql .= " FROM user AS u";
        $sql .= " LEFT JOIN belong_to AS b ON u.rowid = b.fk_user" ;
        $sql .= " WHERE b.fk_team = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idTeam]);

        return $requete->fetchAll(PDO::FETCH_OBJ);
    }

    public function fetchByEmail($email)
    {
        $sql = "SELECT *"; 
        $sql .= " FROM user"; 
        $sql .= " WHERE email = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$email]);

        if($requete->rowCount() > 0)
        {
            $obj = $requete->fetch(PDO::FETCH_OBJ);

            $this->rowid = $obj->rowid;
            $this->lastname = $obj->lastname;
            $this->firstname = $obj->firstname;
            $this->birth = $obj->birth;
            $this->password = $obj->password;
            $this->email = $obj->email;
            // $this->Organization = new Organization($obj->fk_organization);
            $this->fk_organization = $obj->fk_organization;
            $this->consent = $obj->consent;
            $this->admin = $obj->admin;

            $BelongsTo = new BelongsTo();

            $this->BelongsTo = $BelongsTo->fetchAll($obj->rowid);
            
            // $BelongsTo = new BelongsTo();
            // $teamIds = $BelongsTo->fetchTeamIds($obj->rowid);
            // foreach($teamIds as $teamId)
            // {
            //     $this->teams[] = new Team($teamId->fk_team);
            // }
        }
    }

    public function fetchByIds(array $usersIds)
    {
        $usersIds = implode("', '", $usersIds);

        $sql = "SELECT *";
        $sql .= " FROM user";
        $sql .= " WHERE rowid IN ('".$usersIds."')";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetchAll(PDO::FETCH_OBJ);
    }

    // INSERT

    public function create()
    {      
        $sql = "INSERT INTO user (lastname, firstname, birth, email, fk_organization, password, consent, admin) ";
        $sql .= "VALUES (?,?,?,?,?,?,?,?)";
        $requete = $this->getBdd()->prepare($sql);

        $requete->execute([$this->lastname, $this->firstname, $this->birth, $this->email, $this->fk_organization, $this->password, $this->consent, $this->admin]);
    }


    // UPDATE

    public function update()
    {
        $sql = "UPDATE user";
        $sql .= " SET";
        $sql .= " firstname = ?";
        $sql .= " , lastname = ?";
        $sql .= " , email = ?";
        $sql .= " , password = ?";
        $sql .= " , birth = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$this->firstname,$this->lastname,$this->email,$this->password,$this->birth,$this->rowid]);
    }

    public function updateConsent($Consent, int $rowid = null)
    {
        $idUser = $this->rowid ? $this->rowid : $rowid;

        $sql = "UPDATE user";
        $sql .= " SET consent = ?,";
        $sql .= " consent_date = NOW()";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$Consent, $rowid]);
    }


    // DELETE

    public function delete()
    {
        $sql = "DELETE FROM user WHERE rowid = ?";
        //* on trigger
        // $sql .= "DELETE FROM task_member WHERE fk_user = ?;";
        // $sql .= "DELETE FROM task_comment WHERE fk_user = ?;";
        // $sql .= "DELETE FROM belong_to WHERE fk_user = ?;";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$this->rowid, $this->rowid, $this->rowid, $this->rowid]);
    }
    
    public function addCookie($idUser, $token)
    {
        $sql = "UPDATE user";
        $sql .= " SET token = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$token, $idUser]);
    }
}
