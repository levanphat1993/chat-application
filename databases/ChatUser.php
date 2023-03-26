<?php

// ChatUser.php

class ChatUser
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $profile;
    private $status;
    private $created_on;
    private $verification_code;
    private $login_status;
    public $connect;


    public function __construct()
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


    public function setName($_name)
    {
        $this->name= $_name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setEmail($_email)
    {
        $this->email = $_email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setPassword($_password)
    {
        $this->password = $_password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setProfile($_profile)
    {
        $this->profile = $_profile;
    }

    public function getProfile()
    {
        return $this->profile;
    }

    public function setStatus($_status)
    {
        $this->status = $_status;
    }   

    public function getStatus()
    {
        return $this->status;
    }

    public function setCreatedOn($_createdOn)
    {
        $this->created_on = $_createdOn;
    }

    public function getCreatedOn()
    {
        return $this->created_on;
    }

    public function setVerificationCode($_verificationCode)
    {
        $this->verification_code = $_verificationCode;
    }

    public function getVerificationCode()
    {
        return $this->verification_code;
    }

    public function setLoginStatus ($_loginStatus)
    {
        $this->login_status = $_loginStatus;
    }

    public function getLoginStatus () 
    {
        return $this->login_status;
    }

    public function makeAvatar($character)
    {
      
        $path = "images/". time() . ".png";
        $image = imagecreate(200, 200);
        $red = rand(0, 255);
        $green = rand(0,255);
        $bule = rand(0,255);
        imagecolorallocate($image, $red, $green, $bule);
        $textcolor = imagecolorallocate($image, 255, 255, 255);
        $font = dirname(__FILE__) . '/font/arial.ttf';
        imagettftext($image, 100, 0, 55, 150, $textcolor, $font, $character);
        imagepng($image, $path);
        imagedestroy($image);
        
        return $path;
    }

    public function getUserDataByEmail()
    {
        $query = "SELECT * FROM users WHERE email = :email";

        $statement = $this->connect->prepare($query);
        $statement->bindParam(':email', $this->email);

        if ($statement->execute()) {
            $user = $statement->fetch(PDO::FETCH_ASSOC);
        }

        return $user;
    }

    public function save()
    {

        $query = "
		    INSERT INTO users (name, email, password, profile, status, created_on, verification_code) 
		    VALUES (:name, :email, :password, :profile, :status, :created_on, :verification_code)
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':name', $this->name);
		$statement->bindParam(':email', $this->email);
		$statement->bindParam(':password', $this->password);
		$statement->bindParam(':profile', $this->profile);
		$statement->bindParam(':status', $this->status);
		$statement->bindParam(':created_on', $this->created_on);
		$statement->bindParam(':verification_code', $this->verification_code);

		if($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
    }

    public function isValidEmailVerificationCode()
    {
        $query = '
            SELECT * FROM users WHERE verification_code = :verification_code
        ';
        
        $statement = $this->connect->prepare($query);
        $statement->bindParam(':verification_code', $this->verification_code);
        $statement->execute();
        
        return $statement->rowCount() > 0 ? true : false;
    }

    public function enableUserAccount()
    {
        $query = '
            UPDATE users SET status = :status
            WHERE verification_code = :verification_code
        ';

        $statement = $this->connect->prepare($query);
        $statement->bindParam(':status', $this->status);
        $statement->bindParam(':verification_code', $this->verification_code);

        return $statement->execute() ? true : false;
    }

    public function updateUserloginData()
    {
       
        $query = '
            UPDATE users SET login_status = :login_status
            WHERE id = :id
        ';
       
        $statement = $this->connect->prepare($query);
        $statement->bindParam(':login_status', $this->login_status);
        $statement->bindParam(':id', $this->id);

        return $statement->execute() ? true : false;
    }

    public function getUserDataById()
    {
        $query = "
		SELECT * FROM users 
		WHERE id = :id";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':id', $this->id);

		try
		{
			if($statement->execute())
			{
				$user_data = $statement->fetch(PDO::FETCH_ASSOC);
			}
			else
			{
				$user_data = array();
			}
		}
		catch (Exception $error)
		{
			echo $error->getMessage();
		}

		return $user_data;
    }
    
    public function updateImage($_user_profile)
    {
        $extension = explode('.', $_user_profile['name']);
        $new_name = rand() . '.' . $extension[1];
        $destination = 'images/' . $new_name;
        move_uploaded_file($_user_profile['tmp_name'], $destination);
        
        return $destination;
    }

    public function updateData()
    {
        $query = "
            UPDATE users 
            SET name = :name, email = :email, password = :password, profile = :profile
            WHERE id = :id
        ";

        $statement = $this->connect->prepare($query);
        $statement->bindParam(':name', $this->name);
        $statement->bindParam(':email', $this->email);
        $statement->bindParam(':password', $this->password);
        $statement->bindParam(':profile', $this->profile);
        $statement->bindParam(':id', $this->id);

        return $statement->execute() ? true : false;
    }

    public function getUserAllData()
    {
        $query = "
            SELECT * FROM users WHERE 1
        ";

        $statement = $this->connect->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function getUserAllDataWithStatusCount()
    {
        $query = "
                SELECT
                    id,
                    name,
                    profile,
                    login_status,
                    (
                        SELECT
                            COUNT(*)
                        FROM
                            chat_message
                        WHERE
                            to_user_id = :id AND from_user_id = users.id AND status = 'No' ) AS count_status
                FROM
                    users;
        ";

        $statement = $this->connect->prepare($query);
        $statement->bindParam(':id', $this->id);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>