<?php 

class ChatRooms 
{
    private $id;
    private $user_id;
    private $message;
    private $created_on;
    protected $connect;

    function __construct ()
    {
        require_once('Database_connection.php');
        $database_object = new DatabaseConnection;
        $this->connect = $database_object->connect();
    }

    public function setId($_id)
    {
        $this->id = $_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUserId($_user_id)
    {
        $this->user_id = $_user_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setMessage($_message)
    {
        $this->message = $_message;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setCreatedOn($_created_on)
    {
        $this->created_on = $_created_on;
    }
    
    public function getCreatedOn()
    {
        return $this->created_on;
    }

    public function save()
    {
        $query = "
            INSERT INTO chatrooms (user_id, msg, created_on)
            VALUES (:user_id, :msg, :created_on) 
        ";
        $statement = $this->connect->prepare($query);

        $statement->bindParam(':user_id', $this->user_id);
        $statement->bindParam(':msg', $this->message);
        $statement->bindParam(':created_on', $this->created_on);

        return $statement->execute() ? true : false;
    }

    public function getAllChatData()
    {
        $query = "
            SELECT * FROM chatrooms
                INNER JOIN users ON users.id = chatrooms.user_id
                ORDER BY chatrooms.id ASC;
        ";

        $statement = $this->connect->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>