<?php

//require_once '..\\vendor\\autoload.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of Prof
 *
 * @author noffa
 */
class Prof {
    
    private $nom, $prenom, $datenaiss, $lieunaiss;

    /**
     * Prof constructor.
     * @param $nom
     * @param $prenom
     * @param $datenaiss
     * @param $lieunaiss
     */
    public function __construct($nom, $prenom, $datenaiss=null, $lieunaiss=null)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->datenaiss = $datenaiss;
        $this->lieunaiss = $lieunaiss;
    }

    /**
     * @param PDO $conn
     * @return bool
     */
    public function add(\PDO $conn)
    {
        $ret = false;
        $sql = "INSERT INTO prof(nom, prenom, datenaiss, lieunaiss) VALUES ( " . $conn->quote($this->nom) . ", " . $conn->quote($this->prenom) . "," . $conn->quote($this->datenaiss) . "," . $conn->quote($this->lieunaiss) .");";
        //print "$sql \n";
        try {
            $stmt = $conn->query($sql);
            $stmt->closeCursor();
            $ret = true;
        } catch(\PDOException $e) {
            echo $sql . "\n" . $e->getMessage();
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
        $sql = "SELECT COUNT(*) nbr FROM prof;";
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
        $stmt = $conn->query("SELECT * FROM prof;");
        $prof_a = [];
        while($row = $stmt->fetch()) {
            $prof_a[] = new Prof( $row["nom"], $row["prenom"], $row["datenaiss"], $row["lieunaiss"]);
            //echo "* " . $row["id"]. ") " . $row["nom"]. " " . $row["prenom"]. " - " . $row["datenaiss"] ." ". $row["lieunaiss"] . "\n";
        }
        $stmt->closeCursor();
        return $prof_a;
    }

    /**
     * @param PDO $conn
     * @param int|null $id
     * @return Prof|null
     */
    public static function printOne(\PDO $conn, $id=null) {
        if(is_null($id)){
            $id = self::getOneId($conn);
        }
        $stmt = $conn->query("SELECT * FROM prof WHERE idprof = $id;");
        $row = $stmt->fetch();
        $prof = null;
        if($row) {
            $prof = new Prof( $row["nom"], $row["prenom"], $row["datenaiss"], $row["lieunaiss"]);
            //echo "* " . $row["id"]. ") " . $row["nom"]. " ". $row["prenom"]. " | " . $row["datenaiss"]. " - " . $row["lieunaiss"] ."\n";
        } else {
            echo "Aucun enregistrement dans la table prof ayant l'ID $id \n";
        }
        $stmt->closeCursor();
        return $prof;
    }

    /**
     * @param \PDO $conn
     * @return int
     */
    public static function getOneId(\PDO $conn)
    {
        $ret = -1;
        $sql = "SELECT * FROM prof LIMIT 1;";
        try{
            $stmt = $conn->query($sql);
            $row = $stmt->fetch();
            $stmt->closeCursor();
            if($row) {
                $ret = $row["idprof"];
            } else {
                $ret = -1;
                echo "Aucun ENregistrement dans la table prof \n";
            }
        } catch (\PDOException $e){
            $ret = -1;
            echo $sql . "\n" . $e->getMessage();
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
        $sql = "DELETE FROM prof WHERE idprof=$id;";
        try {
            $stmt = $conn->query($sql);
            $stmt->closeCursor();
            $ret = true;
            echo "Suppression du prof num $id REUSSIE. \n";
        } catch(\PDOException $e) {
            $ret = false;
            echo $sql . "\n" . $e->getMessage();
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
        if(is_null($id)){
            $id = self::getOneId($conn);
        }
        $sql = "UPDATE prof SET nom= " . $conn->quote($this->nom) . ", prenom= " . $conn->quote($this->prenom) . ", datenaiss= " . $conn->quote($this->datenaiss) . ", lieunaiss= " . $conn->quote($this->lieunaiss) ." WHERE idprof=$id;";
        try {
            $stmt = $conn->query($sql);
            $ret = (self::printOne($conn, $id) !== null);
            $stmt->closeCursor();
            print "Mise à jour du prof num $id REUSSIE. \n";
        } catch(\PDOException $e) {
            $ret = false;
            echo $sql . "\n" . $e->getMessage();
        } finally {
            return $ret;
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
        return  "=> $this->nom $this->prenom | $this->datenaiss - $this->lieunaiss \n";
    }


    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     * @return Prof
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     * @return Prof
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDatenaiss()
    {
        return $this->datenaiss;
    }

    /**
     * @param mixed $datenaiss
     * @return Prof
     */
    public function setDatenaiss($datenaiss)
    {
        $this->datenaiss = $datenaiss;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLieunaiss()
    {
        return $this->lieunaiss;
    }

    /**
     * @param mixed $lieunaiss
     * @return Prof
     */
    public function setLieunaiss($lieunaiss)
    {
        $this->lieunaiss = $lieunaiss;
        return $this;
    }
    
}
