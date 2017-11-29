<?php

	class Buyer {

		private $db; 
	    private $error; 

	    function __construct($db_conn)
	    {
	        $this->db = $db_conn;

	        if(!isset($_SESSION)){
                session_start();
            }
	    }

	    public function register($nama_depan, $username, $password)
	    {
	        try
	        {
	            // buat hash dari password yang dimasukkan
	            $hashPasswd = password_hash($password, PASSWORD_DEFAULT);

	            //Masukkan user baru ke database
	            $query = $this->db->prepare("INSERT INTO customer(nama, username, password, tgl_daftar) VALUES(:nama, :username, :pass, NOW())");
	            $query->bindParam(":nama", $nama_depan);
	            $query->bindParam(":username", $username);
	            $query->bindParam(":pass", $hashPasswd);
	            $query->execute();

	            return true;
	        }catch(PDOException $e){
	            // Jika terjadi error
	            if($e->errorInfo[0] == 23000){
	                //errorInfor[0] berisi informasi error tentang query sql yg baru dijalankan
	                //23000 adalah kode error ketika ada data yg sama pada kolom yg di set unique
	                $this->error = "Username sudah digunakan!";
	                return false;
	            }else{
	                echo $e->getMessage();
	                return false;
	            }
	        }
	    }

	    public function login($username, $password)
	    {
	        try
	        {
	            // Ambil data dari database
	            $query = $this->db->prepare("SELECT * FROM customer WHERE username = :username");
	            $query->bindParam(":username", $username);
	            $query->execute();
	            $data = $query->fetch();

	            // Jika jumlah baris > 0
	            if($query->rowCount() > 0){
	                // jika password yang dimasukkan sesuai dengan yg ada di database
	                if(password_verify($password, $data['password'])){
	                    $_SESSION['user_session'] = $data['id_customer'];
	                    return true;
	                }else{
	                    $this->error = "<i><small>Username atau Password Salah</small></i>";
	                    return false;
	                }
	            }else{
	                $this->error = "Akun tidak ada";
	                return false;
	            }
	        } catch (PDOException $e) {
	            echo $e->getMessage();
	            return false;
	        }
	    }

	    public function isUserLoggedIn(){
		    // Apakah user_session sudah ada di session
		    if(isset($_SESSION['user_session']))
		    {
		        return true;
		    }
		}

		// Ambil data user yang sudah login
		public function getUser(){
		    // Cek apakah sudah login
		    if(!$this->isUserLoggedIn()){
		    	header('location: index.php?error');
		        return false;
		    }

		    try {
		        // Ambil data user dari database
		        $query = $this->db->prepare("SELECT * FROM customer WHERE id_customer = :id_customer");
		        $query->bindParam(":id_customer", $_SESSION['user_session']);
		        $query->execute();
		        return $query->fetch();
		    } catch (PDOException $e) {
		        echo $e->getMessage();
		        return false;
		    }
		}

		public function updateUser($fields = array(), $id_customer) {

            $set = '';
            $x = 1;

            foreach ($fields as $name => $value) {
                $set .= "{$name} = '{$value}'";
                if($x < count($fields)) {
                    $set .= ', ';
                }
                $x++;
            }

            //var_dump($set);
            $sql = "UPDATE customer SET {$set} WHERE id_customer={$id_customer}";

            if ($this->db->prepare($sql)) {
                if ($this->db->exec($sql)) {
                    return true;
                }
            }

            return false;
        }

        public function ubahPassUser($id, $old, $new) {

            // cek old password

            $cek = "SELECT password FROM customer WHERE id_customer=:id";
            $stmt = $this->db->prepare($cek);
            $stmt->execute(array(":id"=>$id));
            $pass=$stmt->fetch(PDO::FETCH_ASSOC);

            $newPass = password_hash($new, PASSWORD_DEFAULT);

            if($stmt->rowCount()>0) {

                if(password_verify($old, $pass['password'])) {

                    $new = "UPDATE customer SET password='{$newPass}' WHERE id_customer={$id}";

                    $stmtC = $this->db->prepare($new);
                    $stmtC->execute();

                    header("location: index.php?success");
                    return true;
                } else {
                    header("location: index.php?failed");
                }
            }
            
        }

	    public function logout(){
	        // Hapus session
	        session_destroy();
	        // Hapus user_session
	        unset($_SESSION['user_session']);
	        return true;
	    }

	    public function getLastError(){
	        return $this->error;
	    }
	}

?>