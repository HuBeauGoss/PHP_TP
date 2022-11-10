<?php

//require_once '../vendor/autoload.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of Cours
 *
 * @author noffa
 */
class Cours {
    //put your code here
    private $intitule, $duree, $prof;

    /**
     * Cours constructor.
     * @param $intitule
     * @param $duree
     * @param $prof
     */
    public function __construct($intitule, $duree, $prof=null) {
        $this->intitule = $intitule;
        $this->duree = $duree;
        $this->prof = $prof;
    }

    /**
     * @param $conn
     * @return bool
     */
    public function add(\PDO $conn) {
        $ret = false;
        if(empty($this->prof)) {
            $this->setProf(Prof::getOneId($conn));
        }
        if($this->prof == -1) {
            $this->setProf(3);
        }

        $sql = "INSERT INTO cours(intitule, duree, idprof) VALUES ( ". $conn->quote($this->intitule) .", ". $conn->quote($this->duree) . ", $this->prof );";
        try {
            $stmt = $conn->query($sql);
            $stmt->closeCursor();
            $ret = true;
        } catch(\PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        } finally {
            return $ret;
        }
    }

    /**
     * @param PDO $conn
     * @return int
     */
    public static function count(\PDO $conn):int
    {
        $ret = -1;
        $sql = "SELECT COUNT(*) nbr FROM cours;";
        try {
            $stmt = $conn->query($sql);
            $row = $stmt->fetch();
            $stmt->closeCursor();
            if($row) {
                $ret = $row["nbr"];
            }
        } catch (\PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        } finally {
            return $ret;
        }
    }

    /**
     * @param PDO $conn
     * @return array
     */
    public static function printAll(\PDO $conn) {
        $stmt = $conn->query("SELECT * FROM cours;");

        $cours_a = [];
        while($row = $stmt->fetch()) {
            $cours_a[] = new Cours($row["intitule"], $row["duree"], $row["idprof"]);
        }
        $stmt->closeCursor();
        return $cours_a;
    }

    /**
     * @param PDO $conn
     * @param int|null $id
     * @return Cours|null
     */
    public static function printOne(\PDO $conn, $id=null) {
        if(is_null($id)){
            $id = self::getOneId($conn);
        }
        $stmt = $conn->query("SELECT * FROM cours WHERE idcours=$id;");
        $row = $stmt->fetch();
        $stmt->closeCursor();
        $cours = null;
        if($row) {
            $cours = new Cours($row["intitule"], $row["duree"], $row["idprof"]);
        } else {
            echo "Value not found for id $id in the table cours \n";
        }
        return $cours;
    }

    /**
     * This function return the first row of the table.
     *
     * @param $conn
     * @return int
     */
    public static function getOneId(\PDO $conn) {
        $ret = -1;
        $sql = "SELECT * FROM cours LIMIT 1;";
        try{
            $stmt = $conn->query($sql);
            $row = $stmt->fetch();
            $stmt->closeCursor();
            if($row) {
                $ret = $row['idcours'];
            } else {
                $ret = -1;
                print "Aucun enregistrement dans la table cours. \n";
            }
        } catch (\PDOException $e){
            $ret = -1;
            echo $sql . "<br>" . $e->getMessage();
        } finally {
            return $ret;
        }
    }

    /**
     * @param $conn
     * @param null $id
     * @return bool
     */
    public static function deleteOne(\PDO $conn, $id=null) {
        $ret = false;
        if(is_null($id)){
            $id = self::getOneId($conn);
        }
        $sql = "DELETE FROM cours WHERE idcours=$id;";
        try {
            $stmt = $conn->query($sql);
            $stmt->closeCursor();
            $ret = true;
            print "Suppression du cours num $id REUSSIE. \n";
        } catch(\PDOException $e) {
            $ret = false;
            echo $sql . "<br>" . $e->getMessage();
        } finally {
            return $ret;
        }
    }

    /**
     * @param PDO $conn
     * @param null $id
     * @return bool
     */
    public function updateOne(\PDO $conn, $id=null)
    {
        $ret = false;
        if(is_null($id)) {
            $id = self::getOneId($conn);
        }
        $sql = "UPDATE cours SET intitule= ".$conn->quote($this->intitule).", duree=".$conn->quote($this->duree).", idprof= $this->prof WHERE idcours=$id;";
        try {
            $stmt = $conn->query($sql);
            if(self::printOne($conn, $id)){
                $ret= true;
            }
            $stmt->closeCursor();
            print "Mise à Jour du cours num $id REUSSIE. \n";
        } catch(\PDOException $e) {
            $ret = false;
            echo $sql . "<br>" . $e->getMessage();
        } finally {
            return $ret;
        }
    }

    /**
     * Get prof of this cours record.
     * @param PDO $conn
     * @return Prof|null
     */
    public function getMyProf(\PDO $conn) {
        $prof = null;
        $sql = "SELECT * FROM prof WHERE idprof = $this->prof;";
        try {
            $stmt = $conn->query($sql);
            $row = $stmt->fetch();
            $stmt->closeCursor();
            if($row) {
                $prof = new Prof($row["nom"], $row["prenom"], $row["datenaiss"], $row["lieunaiss"]);
            }
        } catch(\PDOException $e) {
            $prof = null;
            echo $sql . "<br>" . $e->getMessage();
        } finally {
            return $prof;
        }
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     */
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return "=> $this->intitule - $this->duree | $this->prof \n";
    }


    /**
     * @return mixed
     */
    public function getIntitule()
    {
        return $this->intitule;
    }

    /**
     * @param mixed $intitule
     * @return Cours
     */
    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * @param mixed $duree
     * @return Cours
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;
        return $this;
    }

    /**
     * @return null
     */
    public function getProf()
    {
        return $this->prof;
    }

    /**
     * @param null $prof
     * @return Cours
     */
    public function setProf($prof)
    {
        $this->prof = $prof;
        return $this;
    }
}
