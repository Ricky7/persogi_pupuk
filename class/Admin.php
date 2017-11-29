<?php

	class Admin {

		private $db; 
	    private $error; 

	    function __construct($db_conn)
	    {
	        $this->db = $db_conn;

	        if(!isset($_SESSION)){
                session_start();
            }
	    }

	    public function getKategori() {

            try {
                // Ambil data kategori dari database
                $query = $this->db->prepare("SELECT * FROM kategori");
                $query->execute();
                return $query->fetchAll();
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

	    public function login($username, $password)
	    {
	        try
	        {
	            // Ambil data dari database
	            $query = $this->db->prepare("SELECT * FROM admin WHERE username = :username");
	            $query->bindParam(":username", $username);
	            $query->execute();
	            $data = $query->fetch();

	            // Jika jumlah baris > 0
	            if($query->rowCount() > 0){
	                // jika password yang dimasukkan sesuai dengan yg ada di database
	                if(password_verify($password, $data['password'])){
	                    $_SESSION['user_session'] = $data['id_admin'];
	                    return true;
	                }else{
	                    $this->error = "Username atau Password Salah";
	                    return false;
	                }
	            }else{
	                $this->error = "Akun tidak ditemukan";
	                return false;
	            }
	        } catch (PDOException $e) {
	            echo $e->getMessage();
	            return false;
	        }
	    }

	    public function tambahAdmin($fields = array())
	    {

	        $keys = array_keys($fields);

	        $values = "'" . implode( "','", $fields ) . "'";

	        $sql = "INSERT INTO tbl_admin (`" . implode('`,`', $keys) . "`) VALUES ({$values})";

	        if ($this->db->prepare($sql)) {
	            if ($this->db->exec($sql)) {
	                return true;
	            }
	        }

	        return false;

	    }

	    public function cekLogin() {

		    if(!self::isLoggedIn()){
		        header("location: admin_login.php");
		    }
	    }

	    public function isLoggedIn(){
	        if(isset($_SESSION['user_session']))
	        {
	            return true;
	        }
	    }

	    public function getAdmin(){
	        // Cek apakah sudah login
	        if(!$this->isLoggedIn()){
	            return false;
	        }

	        try {
	            // Ambil data Pengurus dari database
	            $query = $this->db->prepare("SELECT * FROM admin WHERE id_admin = :id");
	            $query->bindParam(":id", $_SESSION['user_session']);
	            $query->execute();
	            return $query->fetch();
	        } catch (PDOException $e) {
	            echo $e->getMessage();
	            return false;
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

	    public function listKategori($query) {

            $stmt = $this->db->prepare($query);
            $stmt->execute();
        
            if($stmt->rowCount()>0)
            {
                while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                {
                    ?>

                    <tr>
                        <td><?php print($row['id_kategori']); ?></td>
                        <td><?php print($row['nama_kategori']); ?></td>
                        <td><?php print($row['desk_kategori']); ?></td>
                        <td>
                        <button type="button" class="btn btn-info btn-xs edit_button" 
                            data-toggle="modal" data-target="#myModalEdit"
                            data-nama="<?php print($row['nama_kategori']);?>"
                            data-desk="<?php print($row['desk_kategori']);?>"
                            data-id="<?php print($row['id_kategori']); ?>">
                            Edit
                        </button>
                        <a href="#" data-href="a_kategori_del.php?id=<?php print($row['id_kategori']); ?>" data-toggle="modal" data-nama="<?php print($row['nama_kategori']);?>" data-target="#confirm-delete" class="btn btn-danger btn-xs">Remove</a> 
                        </td> 
                    </tr>
                    <?php
                }
            }
            else
            {
                ?>
                <tr>
                <td>Tidak ditemukan....</td>
                </tr>
                <?php
            }

        }

	    public function insertKategori($fields = array()) {

            $keys = array_keys($fields);

            $values = "'" . implode( "','", $fields ) . "'";

            $sql = "INSERT INTO kategori (`" . implode('`,`', $keys) . "`) VALUES ({$values})";

            if ($this->db->prepare($sql)) {
                if ($this->db->exec($sql)) {
                    return true;
                }
            }

            return false;

        }

        public function editKategori($fields = array(), $id) {

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
            $sql = "UPDATE kategori SET {$set} WHERE id_kategori = {$id}";

            if ($this->db->prepare($sql)) {
                if ($this->db->exec($sql)) {
                    return true;
                }
            }

            return false;
        }

        public function delKategori($che_id) {
            $stmt = $this->db->prepare("DELETE FROM kategori WHERE id_kategori=:che_id");
            $stmt->bindparam(":che_id",$che_id);
            $stmt->execute();
            return true;
        }

        public function listProduk($query) {

        	$jumlah_desimal = "0";
	        $pemisah_desimal = ",";
	        $pemisah_ribuan = ".";

            $stmt = $this->db->prepare($query);
            $stmt->execute();
        
            if($stmt->rowCount()>0)
            {
                while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                {
                    ?>

                    <tr>
                    	<td>
                    		<img src="../assets/gambar_produk/<?php print($row['gambar']); ?>" class="produk">
                    	</td>
                        <td><?php print($row['nama_produk']); ?></td>
                        <td><?php print($row['nama_kategori']); ?></td>
                        <td><?php print($row['jenis']); ?></td>
                        <td><?php print('Rp.'.number_format($row['harga'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan)) ?></td>
                        <td><?php print($row['stok']); ?></td>
                        <td>
                        <button type="button" class="btn btn-info btn-xs edit_button" 
                            data-toggle="modal" data-target="#myModalEdit"
                            data-nama="<?php print($row['nama_produk']);?>"
                            data-katid="<?php print($row['id_kategori']);?>"
                            data-kat="<?php print($row['nama_kategori']);?>"
                            data-jenis="<?php print($row['jenis']);?>"
                            data-stok="<?php print($row['stok']);?>"
                            data-harga="<?php print($row['harga']);?>"
                            data-desk="<?php print($row['desk_produk']);?>"
                            data-id="<?php print($row['id_produk']); ?>">
                            Edit
                        </button>
                        <a href="#" data-href="a_produk_del.php?id=<?php print($row['id_produk']); ?>" data-toggle="modal" data-nama="<?php print($row['nama_produk']);?>" data-target="#confirm-delete" class="btn btn-danger btn-xs">Remove</a> 
                        </td> 
                    </tr>
                    <?php
                }
            }
            else
            {
                ?>
                <tr>
                <td>Tidak ditemukan....</td>
                </tr>
                <?php
            }

        }

        public function listAnggota($query) {

            $jumlah_desimal = "0";
            $pemisah_desimal = ",";
            $pemisah_ribuan = ".";

            $stmt = $this->db->prepare($query);
            $stmt->execute();
        
            if($stmt->rowCount()>0)
            {
                while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                {
                    ?>

                    <tr>
                        <td><?php print($row['nama']); ?></td>
                        <td><?php print($row['username']); ?></td>
                        <td><?php print($row['status']); ?></td>
                        <td><?php print($row['no_hp']); ?></td>
                        <td>
                        <button type="button" class="btn btn-info btn-xs member_button" 
                            data-toggle="modal" data-target="#myModalMember"
                            data-username="<?php print($row['username']);?>"
                            data-status="<?php print($row['status']);?>"
                            data-id="<?php print($row['id_customer']); ?>">
                            Edit
                        </button>
                        </td> 
                    </tr>
                    <?php
                }
            }
            else
            {
                ?>
                <tr>
                <td>Tidak ditemukan....</td>
                </tr>
                <?php
            }

        }

        public function insertProduk($fields = array()) {

            $keys = array_keys($fields);

            $values = "'" . implode( "','", $fields ) . "'";

            $sql = "INSERT INTO produk (`" . implode('`,`', $keys) . "`) VALUES ({$values})";

            if ($this->db->prepare($sql)) {
                if ($this->db->exec($sql)) {
                    return true;
                }
            }

            return false;

        }

        public function editProduk($fields = array(), $id) {

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
            $sql = "UPDATE produk SET {$set} WHERE id_produk = {$id}";

            if ($this->db->prepare($sql)) {
                if ($this->db->exec($sql)) {
                    return true;
                }
            }

            return false;
        }

        public function delProduk($che_id) {
            $stmt = $this->db->prepare("DELETE FROM produk WHERE id_produk=:che_id");
            $stmt->bindparam(":che_id",$che_id);
            $stmt->execute();
            return true;
        }

        public function reset() {

            $sql = "UPDATE customer SET kuota=200 WHERE status = 'Verified'";

            if ($this->db->prepare($sql)) {
                if ($this->db->exec($sql)) {
                    return true;
                }
            }

            return false;
        }

        public function ubahStatus($status, $id) {

            if($status == 'Verified') {
                $nilai = 200;
            } else {
                $nilai = 0;
            }

            $sql = "UPDATE customer SET status='{$status}', kuota={$nilai} WHERE id_customer={$id}";

            if ($this->db->prepare($sql)) {
                if ($this->db->exec($sql)) {
                    return true;
                }
            }

            return false;
        }

	    public function paging($query,$records_per_page)
	    {
	        $starting_position=0;
	        if(isset($_GET["page_no"]))
	        {
	            $starting_position=($_GET["page_no"]-1)*$records_per_page;
	        }
	        $query2=$query." limit $starting_position,$records_per_page";
	        return $query2;
	    }

	    public function paginglink($query,$records_per_page)
	    {
	        
	        $self = $_SERVER['PHP_SELF'];
	        
	        $stmt = $this->db->prepare($query);
	        $stmt->execute();
	        
	        $total_no_of_records = $stmt->rowCount();
	        
	        if($total_no_of_records > 0)
	        {
	            ?><ul class="pagination"><?php
	            $total_no_of_pages=ceil($total_no_of_records/$records_per_page);
	            $current_page=1;
	            if(isset($_GET["page_no"]))
	            {
	                $current_page=$_GET["page_no"];
	            }
	            if($current_page!=1)
	            {
	                $previous =$current_page-1;
	                echo "<li><a href='".$self."?page_no=1'>First</a></li>";
	                echo "<li><a href='".$self."?page_no=".$previous."'>Previous</a></li>";
	            }
	            for($i=1;$i<=$total_no_of_pages;$i++)
	            {
	                if($i==$current_page)
	                {
	                    echo "<li><a href='".$self."?page_no=".$i."' style='color:red;'>".$i."</a></li>";
	                }
	                else
	                {
	                    echo "<li><a href='".$self."?page_no=".$i."'>".$i."</a></li>";
	                }
	            }
	            if($current_page!=$total_no_of_pages)
	            {
	                $next=$current_page+1;
	                echo "<li><a href='".$self."?page_no=".$next."'>Next</a></li>";
	                echo "<li><a href='".$self."?page_no=".$total_no_of_pages."'>Last</a></li>";
	            }
	            ?></ul><?php
	        }
	    }
	}
	
?>