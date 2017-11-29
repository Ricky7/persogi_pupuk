<?php

	class Produk {

		private $db; 
	    private $error; 

	    function __construct($db_conn)
	    {
	        $this->db = $db_conn;
	    }


		public function getProdukID($id)
		{
			$stmt = $this->db->prepare("SELECT * FROM produk WHERE id_produk=:id");
			$stmt->execute(array(":id"=>$id));
			$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
			return $editRow;
		}

		public function getCustomerID($id)
		{
			$stmt = $this->db->prepare("SELECT * FROM customer WHERE id_customer=:id");
			$stmt->execute(array(":id"=>$id));
			$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
			return $editRow;
		}

		public function getKategoriID($id)
		{
			$stmt = $this->db->prepare("SELECT * FROM kategori WHERE id_kategori=:id");
			$stmt->execute(array(":id"=>$id));
			$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
			return $editRow;
		}

        public function getCartID($id)
        {
            $stmt = $this->db->prepare("SELECT SUM(jumlah) as jum FROM cart WHERE id_customer=:id");
            $stmt->execute(array(":id"=>$id));
            $editRow=$stmt->fetch(PDO::FETCH_ASSOC);
            return $editRow;
        }

	    public function index($query) {

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
                    	<a href="single.php?slug=<?php print($row['id_produk']) ?>">
							<div class="col-sm-3">
						      <div class="panel panel-success">
						        <div class="panel-heading"><center><?php print($row['nama_produk']) ?></center></div>
						        <div class="panel-body img">
						        	<img src="assets/gambar_produk/<?php print($row['gambar']) ?>" class="img-responsive" style="width:230px;height:200px;" alt="Image">
						        	<?php 
						        		if($row['jenis'] == 'Subsidi') {
						        			?>
						        				<div class="top-right"><h4>SUBSIDI</h4></div>
						        			<?php
						        		}
						        	?>
						        	
						        </div>
						        <div class="panel-footer"><center><?php print('Rp. '.number_format($row['harga'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan)); ?></center></div>
						      </div>
						    </div>
						</a>
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

        public function addCart($datas = array()) {

	        $keys = array_keys($datas);

	        $values = "'" . implode( "','", $datas ) . "'";

	        $id_customer = $datas['id_customer'];
	        $id_produk = $datas['id_produk'];

	        $datax = $this->getCustomerID($id_customer);

	        // Cek Jika Produk tersebut sudah ada di table cart dengan id sesi yg sama
	        $stmt = $this->db->prepare("SELECT * FROM cart WHERE id_customer=:id_customer AND id_produk=:id_produk");
	        $stmt->execute(array(":id_customer"=>$id_customer, ":id_produk"=>$id_produk));
	        $editRow=$stmt->fetch(PDO::FETCH_ASSOC);
	        
	        // jika ada
	        if($stmt->rowCount()>0) {

	        	$harga = $editRow['tharga'] + $datas['tharga'];
	            $jlh_produk = $editRow['jumlah'] + $datas['jumlah'];

	        	//cek jika jumlah produk yg dipesan + jumlah produk dikeranjang tidak lebih besar dari stok
	        	$stmtp = $this->db->prepare("SELECT * FROM produk WHERE id_produk=:id_produk");
		        $stmtp->execute(array(":id_produk"=>$id_produk));
		        $rowP=$stmtp->fetch(PDO::FETCH_ASSOC);

		        //cek jika customer merupakan member verified dan produk yg dibeli subsidi
		        if($datax['status'] == 'Verified' && $rowP['jenis'] == 'Subsidi'){
		        	if($jlh_produk > $datax['kuota']) {
			            header("location: single.php?slug=$id_produk&errKuota");
			            stop();
			        }
		        }

		        //cek jika jumlah produk lebih besar dari stok
		        if($jlh_produk > $rowP['stok']) {
		        	header("location: single.php?slug=$id_produk&msg=$jlh_produk");
		        	stop();
		        }

	            $harga = $editRow['tharga'] + $datas['tharga'];
	            $jlh_produk = $editRow['jumlah'] + $datas['jumlah'];

	            $sql = "UPDATE cart SET tharga={$harga}, jumlah={$jlh_produk}  WHERE id_produk = {$id_produk} AND id_customer = {$id_customer}";


	            if ($this->db->prepare($sql)) {
	                if ($this->db->exec($sql)) {
	                    return true;
	                }
	            }
	            
	            return false;


	        } else {

	            $sql = "INSERT INTO cart (`" . implode('`,`', $keys) . "`) VALUES ({$values})";

	            if ($this->db->prepare($sql)) {
	                if ($this->db->exec($sql)) {
	                    return true;
	                }
	            }

	            return false;
	        }

	        return true;

	    }

	    public function listCart($query) {

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
                    		<a href="single.php?slug=<?php print($row['id_produk']); ?>"><img src="assets/gambar_produk/<?php print($row['gambar']); ?>" class="produk"></a>
                    	</td>
                        <td><?php print($row['nama_produk']); ?></td>
                        <td><?php print($row['jenis']); ?></td>
                        <td><?php print($row['jumlah']); ?></td>
                        <td><?php print('Rp.'.number_format($row['tharga'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan)) ?></td>
                        <td>
                        <a href="#" data-href="hapus_cart.php?id=<?php print($row['id_cart']); ?>" data-toggle="modal" data-nama="<?php print($row['nama_produk']);?>" data-target="#confirm-delete" class="btn btn-danger btn-xs">Remove</a> 
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

        public function getOrderData($id_order){

        try {
            $query = $this->db->prepare("SELECT * FROM tbl_order WHERE id_order = :id_order");
            $query->bindParam(":id_order", $id_order);
            $query->execute();
            return $query->fetch();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

        public function delCart($id) {
            $stmt = $this->db->prepare("DELETE FROM cart WHERE id_cart=:id");
            $stmt->bindparam(":id",$id);
            $stmt->execute();
            return true;
        }

        public function insertOrder($fields = array(), $id_customer) {

	    	$keys = array_keys($fields);

			$values = "'" . implode( "','", $fields ) . "'";

			//var_dump($fields);

			$sql = "INSERT INTO tbl_order (`" . implode('`,`', $keys) . "`) VALUES ({$values})";

			if ($this->db->prepare($sql)) {

		        if ($this->db->exec($sql)) {

		        	$lastId = $this->db->lastInsertId();

		        	$move_data = "INSERT INTO order_detail (id_order, id_produk, jumlah, harga)
	SELECT {$lastId}, id_produk, jumlah, tharga FROM cart WHERE id_customer={$id_customer}";

					if($this->db->exec($move_data)) {
						$delCart = $this->db->prepare("DELETE FROM cart WHERE id_customer=:id");
						$delCart->bindparam(":id",$id_customer);
						$delCart->execute();
						return true;
					}
					
		            return true;
		        }
		    }

			return false;
	    }

	    public function insertBayar($fields = array(), $id_order) {

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
			$sql = "UPDATE tbl_order SET {$set} WHERE id_order={$id_order}";

			if ($this->db->prepare($sql)) {
		        if ($this->db->exec($sql)) {
		            return true;
		        }
		    }

			return false;
	    }

	    public function hapusOrder($id) {
            $stmt = $this->db->prepare("DELETE FROM tbl_order WHERE id_order=:id");
            $stmt->bindparam(":id",$id);
            $stmt->execute();
            return true;
        }

        public function hapusOrderDetail($id) {
            $stmt = $this->db->prepare("DELETE FROM order_detail WHERE id_order=:id");
            $stmt->bindparam(":id",$id);
            $stmt->execute();
            return true;
        }

	    public function listBelum($query) {

            $stmt = $this->db->prepare($query);
            $stmt->execute();
        
            if($stmt->rowCount()>0)
            {
                while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                {
                    ?>

                    <tr>
                        <td><?php print($row['id_order']); ?></td>
                        <td><?php print($row['tgl_order']); ?></td>
                        <td><?php print($row['nama']); ?></td>
                        <td><?php print('Belum Bayar'); ?></td>
                        <td>
                        <a href="#" data-href="a_order_del.php?id=<?php print($row['id_order']); ?>" data-toggle="modal" data-nama="<?php print($row['id_order']);?>" data-target="#confirm-delete" class="btn btn-danger btn-xs">Remove</a> 
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

        public function listPending($query) {

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
                        <td><?php print($row['id_order']); ?></td>
                        <td><?php print($row['tgl_order']); ?></td>
                        <td><?php print($row['nama']); ?></td>
                        <td><?php print("Rp. ".number_format($row['nilai_transfer'], $jumlah_desimal, $pemisah_desimal, $pemisah_ribuan)); ?></td>
                        <td>
                        <a href="a_info_order.php?id=<?php print($row['id_order']); ?>" class="btn btn-danger btn-xs">Open</a> 
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

        public function listBayar($query) {

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
                        <td><?php print($row['id_order']); ?></td>
                        <td><?php print($row['tgl_order']); ?></td>
                        <td><?php print($row['nama']); ?></td>
                        <td><?php print("Rp. ".number_format($row['nilai_transfer'], $jumlah_desimal, $pemisah_desimal, $pemisah_ribuan)); ?></td>
                        <td>
                        <a href="a_info_order.php?id=<?php print($row['id_order']); ?>" class="btn btn-danger btn-xs">Open</a> 
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

        public function listDikirim($query) {

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
                        <td><?php print($row['id_order']); ?></td>
                        <td><?php print($row['tgl_order']); ?></td>
                        <td><?php print($row['nama']); ?></td>
                        <td><?php print("Rp. ".number_format($row['nilai_transfer'], $jumlah_desimal, $pemisah_desimal, $pemisah_ribuan)); ?></td>
                        <td>
                        <a href="a_info_order.php?id=<?php print($row['id_order']); ?>" class="btn btn-danger btn-xs">Open</a> 
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

        public function listSelesai($query) {

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
                        <td><?php print($row['id_order']); ?></td>
                        <td><?php print($row['tgl_order']); ?></td>
                        <td><?php print($row['nama']); ?></td>
                        <td><?php print("Rp. ".number_format($row['nilai_transfer'], $jumlah_desimal, $pemisah_desimal, $pemisah_ribuan)); ?></td>
                        <td>
                        <a href="a_info_order.php?id=<?php print($row['id_order']); ?>" class="btn btn-danger btn-xs">Open</a> 
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

        public function ubahStatusOrder($id, $status, $id_admin) {

	        $sql = "UPDATE tbl_order SET id_admin={$id_admin}, status_order='{$status}' WHERE id_order = {$id}" ;

	        if ($this->db->prepare($sql)) {
	            if ($this->db->exec($sql)) {
	            	if($status == 'Bayar'){
	            		// ambil nilai id_produk & jumlah_produk pada tabel order_detail
			        	$ambil = "SELECT id_produk, jumlah FROM order_detail WHERE id_order={$id}";

			        	$stmt = $this->db->prepare($ambil);
			        	$stmt->execute();

						if($stmt->rowCount()>0)
						{
							while($ambil_row=$stmt->fetch(PDO::FETCH_ASSOC))
							{
								$ambil_stok = $ambil_row['jumlah'];
								$ambil_id_produk = $ambil_row['id_produk'];

								$updateStok = "UPDATE produk SET stok=stok-{$ambil_stok} WHERE id_produk={$ambil_id_produk}";

								$this->db->prepare($updateStok);
								$this->db->exec($updateStok);
							}
						}
						else
						{
							echo "Error";
						}
			            return true;
	            	}

	            	if($status == 'Selesai') {
	            		$sql = "UPDATE tbl_order SET tgl_terkirim=NOW() WHERE id_order = {$id}" ;
	            		$this->db->prepare($sql);
	            		$this->db->exec($sql);
	            		return true;
	            	}
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