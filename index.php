<?php

class User
{
    private int $id;
    private string $login; //должен быть уникальным
    private string $password;
    private string $email;

    public function __construct(string $login, string $password, string $email)
    {
        $this->id = -1;
        $this->login = $login;
        $this->password = $password;
        $this->email = $email;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}

class Repository
{
    private PDO $db;

    public function __construct()
    {
        $MYSQL_log = '127.0.0.1';
        $MYSQL_user = 'admin';
        $MYSQL_bd = 'Users';
        $MYSQL_pass = 'admin';

        try {
            $db = new PDO("mysql:host=$MYSQL_log;dbname=$MYSQL_bd", $MYSQL_user, $MYSQL_pass);
            echo "successful";
        } catch (PDOException $e) {
            printf("ERROR: %s", $e->getMessage());
            die();
        }

        $this->db = $db;
    }

    public function save(User $usr)
    {
        $id = $usr->getId();
        $login = $usr->getLogin();
        $password = $usr->getPassword();
        $email = $usr->getEmail();

        $query = "SELECT ID FROM USER WHERE LOGIN = " . "'$login'";
        $user = $this->db->query($query)->fetch(PDO::FETCH_ASSOC);

        if ($user != null)
            $usr->setId($user['ID']);
        else {
            if ($usr->getId() == -1) {
                $query = "INSERT INTO USER (LOGIN, PASSWORD, EMAIL) VALUES (" . "'$login', " . "'$password', " . "'$email'" . ");";
                $this->db->exec($query);
                $usr->setId($this->db->lastInsertId());
            } else {
                $query = "UPDATE USER SET EMAIL = " . "'$email'" . ", LOGIN = "  . "'$login'" . ", PASSWORD = " . "'$password'"  . "WHERE ID = " . "'$id'";
                $this->db->query($query);
            }
        }
    }

    public function remove(User $usr)
    {
        $id = $usr->getId();
        if ($usr->getId() != -1) {
            $query = "DELETE FROM user WHERE id=$id";
            $this->db->query($query);
        }
    }

    public function getById(int $id)
    {
        $query = "SELECT * FROM USER WHERE ID = " . "'$id'";
        $user = $this->db->query($query)->fetch(PDO::FETCH_ASSOC);
        echo "<br>";
        echo "id: " . $user['id'] . "<br>";
        echo " login: " . $user['login'] . "<br>";
        echo " password: " . $user['password'] . "<br>";
        echo " email: " . $user['email'] . "<br>";
    }

    public function all()
    {
        $query = "SELECT * FROM USER";
        $users = $this->db->query($query)->fetchAll();
        echo "<br>";
        for ($i = 0; $i <= count($users) - 1; $i++) {
            $user = $users[$i];
            echo "id: " . $user['id'] . "<br>";
            echo " login: " . $user['login'] . "<br>";
            echo " password: " . $user['password'] . "<br>";
            echo " email: " . $user['email'] . "<br>";
        }
    }

    public function getByLogin(string $login)
    {
        $query = "SELECT * FROM USER WHERE LOGIN = " . "'$login'";
        $user = $this->db->query($query)->fetch(PDO::FETCH_ASSOC);
        echo "<br>";
        echo "id: " . $user['id'] . "<br>";
        echo " login: " . $user['login'] . "<br>";
        echo " password: " . $user['password'] . "<br>";
        echo " email: " . $user['email'] . "<br>";
    }
}


