<?php
date_default_timezone_set("Asia/Jakarta");
require_once('class.Database.php');
class CtrlGlobal extends Database {	
   	//call insert methods to save data  in database
	function insert($table,$arFieldValues) {
		try {
			$objDB = new Database();
			$sql=$objDB->insert2($table, $arFieldValues);
	   } catch (Exception $e) {
		  echo "Query failure" . NL;
		  echo $e->getMessage();
	    }
   }
   
	//call update methods to save data  in database
	function update($table, $arFieldValues, $arConditions) {
		try {
				$objDB = new Database();
				$sql=$objDB->update2($table, $arFieldValues, $arConditions);
				return "1";
		} catch (Exception $e) {
			  echo "Query failure" . NL;
			  echo $e->getMessage();
		}
	}
	 //call delete methods to delete data in database
	function delete($table,$arFieldValues) {
		try {
			$objDB = new Database();
			$sql=$objDB->delete($table, $arFieldValues);
	   } catch (Exception $e) {
		  echo "Query failure" . NL;
		  echo $e->getMessage();
	    }
   }
	
	function GetGlobalFilter($sql){
		try {
		 $objDB = new Database();
		} catch (Exception $e) {
		 echo $e->getMessage();
		 exit(1);
		}
		try {
			$data=$objDB->select($sql);
		} catch (Exception $e) {
		 echo $e->getMessage();
		}
		return $data;
	}
	
   	function getName($sql){
		try {
		 $objDB = new Database();
		} catch (Exception $e) {
		 echo $e->getMessage();
		 exit(1);
		}
		$name="";
		try {
			$data=$objDB->select($sql);
		} catch (Exception $e) {
		 echo $e->getMessage();
		}
		foreach($data as $item):
			$name=$item['name'];
		endforeach;
		return $name;
	}

	function UserAll($sql){
		try {
		 $objDB = new Database();
		} catch (Exception $e) {
		 echo $e->getMessage();
		 exit(1);
		}
		try {
			$data=$objDB->insertAll($sql);
		} catch (Exception $e) {
		 echo $e->getMessage();
		}
		return $data;
	}
	
	//OTHER FUNCTION
	function findFirstAndLastDay($anyDate)
	{
		//$anyDate            =    '2009-08-25';    // date format should be yyyy-mm-dd
		list($yr,$mn,$dt)    =    explode('-',$anyDate);    // separate year, month and date
		$timeStamp            =    mktime(0,0,0,$mn,1,$yr);    //Create time stamp of the first day from the give date.
		$firstDay            =     date('d/m/Y',$timeStamp);    //get first day of the given month
		list($y,$m,$t)        =     explode('-',date('Y-m-t',$timeStamp)); //Find the last date of the month and separating it
		$lastDayTimeStamp    =    mktime(0,0,0,$m,$t,$y);//create time stamp of the last date of the give month
		$lastDay            =    date('d/m/Y',$lastDayTimeStamp);// Find last day of the month
		$arrDay                =    array("$firstDay","$lastDay"); // return the result in an array format.
	   
		return $arrDay;
	} 
	function getNoPayablePayment(){
		$month=date("m");
		$year= date("y");
		global $cfg;
		$objDB = new Database();
		try {
			$query="SELECT max(substr(payable_payments_code,-4)) as id FROM a_payable_payment WHERE substr(payable_payments_code,1,10) = 'PAY/".$year."/".$month."/'";
			$data=$objDB->select($query);
			foreach($data as $item):
				if(!isset($item['id'])) {
					$maxid='PAY/'.$year."/".$month."/0001";
			    	} else {
					$maxid='PAY/'.$year."/".$month."/".str_pad($item['id']+1, 4, "0", STR_PAD_LEFT);	
				}
			endforeach;
		} catch (Exception $e) {
			echo "Query failure" . NL;
			echo $e->getMessage();
		}
		return $maxid;
	}
	function generateNumber($length = 10) {
	    $characters = '0123456789';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}
	function nicetime($date) {
	    if(empty($date)) {
	        return "No date provided";
	    }
	    
	    $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	    $lengths         = array("60","60","24","7","4.35","12","10");
	    
	    $now             = time();
	    $unix_date         = strtotime($date);
	    
	       // check validity of date
	    if(empty($unix_date)) {    
	        return "Bad date";
	    }

	    // is it future date or past date
	    if($now > $unix_date) {    
	        $difference     = $now - $unix_date;
	        $tense         = "ago";
	        
	    } else {
	        $difference     = $unix_date - $now;
	        $tense         = "from now";
	    }
	    
	    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
	        $difference /= $lengths[$j];
	    }
	    
	    $difference = round($difference);
	    
	    if($difference != 1) {
	        $periods[$j].= "s";
	    }
	    
	    return "$difference $periods[$j] {$tense}";
	}

	function generateRandomString($length = 10) {
	    $characters = '0123456789ARIFRAGILPAMUNGKAS';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	function konversi_tanggal($val) {
		$tgl = date('w-j-n-Y-h-i', strtotime($val));
		$date = explode('-', $tgl);
		
		$hari = array("Minggu","Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu");
		$bulan = array("","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
		echo $hari[$date[0]].", ".$date[1]." ".$bulan[$date[2]]." ".$date[3]." ".$date[4].":".$date[5];
	}
	function sentMailer($email, $name, $message) {
		// Menambahkan atau menginclude auto load PHPMailer
		require 'phpmailer/PHPMailerAutoload.php';
		// Membuat instance PHPMailer
		$mail = new PHPMailer;
		// Checking penggunaan SMTP
		$mail->isSMTP();
		// Mengaktifkan mode debugging PHPMailer
		// 0 = untuk Production
		// 1 = Pesan yang ditampilkan untuk sistem client
		// 2 = Pesan yang ditampilkan mencakup sistem php dan juga kesalahan konfigurasi
		// $mail->SMTPDebug = 2;
		// HTML debugging (Hapus jika berada di Production server)
		// $mail->Debugoutput = 'html';
		// Setting hostname, bisa juga menggunakan Ip Address Shared Hosting Pembaca
		$mail->Host = "mail.dewigandhalia.co.id";
		// Port yang dipakai, Umumnya 25
		$mail->Port = 25;
		// Autentifikasi SMTP
		$mail->SMTPAuth = true;
		// Username akun email yang berada di host
		$mail->Username = "info@dewigandhalia.co.id";
		// Password akun email yang berada host
		$mail->Password = "bismillah";
		// Set konfigurasi email berasal. (Akan ditampilkan di email masuk pengguna)
		$mail->setFrom('info@dewigandhalia.co.id', 'Dewi Ghandalia');
		// Set alternatif untuk reply-to (Ditampilkan di email masuk pengguna)
		// $mail->addReplyTo('info@fintag.id', 'Fintag Maritim');
		// Set alamat email yang akan dikirim (Diambil secara dinamis melalui PHP. Sesuaikan !)
		$mail->addAddress($email, $name);
		// Subject
		$mail->Subject = 'Dewi Ghandalia';
		// Template body email yang akan dikirim
		$mail->msgHTML($message);

		// Siap kirim !
		if (!$mail->send()) {
			echo "Gagal !". $mail->ErrorInfo;
		} else {
		//	echo "Email Terkirim";
		}
	}
	function sentZenziva($userkey,$passkey,$message,$no_hp){
		 $userkey="3hg264m6cm"; // userkey lihat di zenziva
            $passkey="arphiyour"; // set passkey di zenziva
            $url = "http://beauty.zenziva.com/apps/smsapi.php";
            $curlHandle = curl_init();
            curl_setopt($curlHandle, CURLOPT_URL, $url);
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, 'userkey='.$userkey.'&passkey='.$passkey.'&nohp='.$no_hp.'&tipe=reguler&pesan='.urlencode($message));
            curl_setopt($curlHandle, CURLOPT_HEADER, 0);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
            curl_setopt($curlHandle, CURLOPT_POST, 1);
            $results = curl_exec($curlHandle);
            curl_close($curlHandle);
            return $results;
	}
	//END OTHER FUNCTION
	//MAKING ID
	function getNoGL($kode,$primary_field,$table,$spend_date){
    $temp = explode('/', $spend_date);
		$month=$temp[1];
		$year= substr($temp[2],2,2);
		$size_code = strlen($kode)+7;
		global $cfg;
		$objDB = new Database();
		try {
			$query="SELECT max(substr($primary_field,11,5)) as id FROM $table where substr($primary_field,1,$size_code) = '".$kode."/".$year."/".$month."/'";
			$data=$objDB->select($query);
			foreach($data as $item):
				if(!isset($item['id'])) {
					$maxid=$kode.'/'.$year.'/'.$month.'/'."0001";
			    	} else {
					$maxid=$kode.'/'.$year.'/'.$month.'/'.str_pad($item['id']+1,4, "0", STR_PAD_LEFT);	
				}
			endforeach;
		} catch (Exception $e) {
			echo "Query failure" . NL;
			echo $e->getMessage();
		}
		return $maxid;
	}
	//MAKING ID
	function getNoGlobalYear($kode,$primary_field,$table){
    $month=date("m");
    $year= date("y");
    $size_code = strlen($kode)+1;
    global $cfg;
    $objDB = new Database();
    try {
      $query="SELECT max(substr($primary_field,-5)) as id FROM $table where substr($primary_field,$size_code,2) = '".$year."'";
      $data=$objDB->select($query);
      foreach($data as $item):
        if(!isset($item['id'])) {
          $maxid=$kode.$year."000001";
            } else {
          $maxid=$kode.$year.str_pad($item['id']+1,6, "0", STR_PAD_LEFT); 
        }
      endforeach;
    } catch (Exception $e) {
      echo "Query failure" . NL;
      echo $e->getMessage();
    }
    return $maxid;
  }
  function getNoGlobalYearDate($kode,$primary_field,$table,$date){
    $temp = explode('/', $date);
		$month=$temp[1];
		$year= substr($temp[2],2,2);
		$size_code = strlen($kode)+1;
		global $cfg;
		$objDB = new Database();
		try {
			$query="SELECT max(substr($primary_field,-5)) as id FROM $table where substr($primary_field,$size_code,2) = '".$year."'";
			$data=$objDB->select($query);
			foreach($data as $item):
				if(!isset($item['id'])) {
					$maxid=$kode.$year."000001";
			    	} else {
					$maxid=$kode.$year.str_pad($item['id']+1,6, "0", STR_PAD_LEFT);	
				}
			endforeach;
		} catch (Exception $e) {
			echo "Query failure" . NL;
			echo $e->getMessage();
		}
		return $maxid;
	}
  function getNoFakturPajakMaxNo(){
    //010.001-185227001
    $month=date("m");
    $year= date("y");
    global $cfg;
    $objDB = new Database();
    try {
      $query="SELECT max(substr(no_faktur_pajak,12,9)) as xid, substr(no_faktur_pajak,7,1) as last_no FROM t_faktur_pajak WHERE substr(no_faktur_pajak,9,2) = '".$year."' AND CHAR_LENGTH(no_faktur_pajak) = '19' order by id desc limit 1";
      $data=$objDB->select($query);
      foreach($data as $item):
        if(!isset($item['xid'])) {
          $maxid='010.00'.$item['last_no'].'-'.$year."0000001";
            } else {
          $maxid='010.00'.$item['last_no'].'-'.$year.".".str_pad($item['xid']+1, 7, "0", STR_PAD_LEFT); 
        }
      endforeach;
    } catch (Exception $e) {
      echo "Query failure" . NL;
      echo $e->getMessage();
    }
    return $maxid;
  }
	function getGlobalID($kode,$primary_field,$table) {
		global $cfg;
		$size_code = strlen($kode)+1;
		$objDB = new Database();
		try {
			$query="SELECT max(substr($primary_field,$size_code,4)) as $primary_field FROM $table ";
			//call select methods to retreive max id Order 00001	 
			$data=$objDB->select($query);
			foreach($data as $item):
				$xid=$item[$primary_field];
				if(!isset($item[$primary_field])) {
					$max_id=$kode."0001";
				}	
				else {
					$max_id=$kode.str_pad($item[$primary_field]+1, 4, "0", STR_PAD_LEFT);
				}	
				endforeach;
		} catch (Exception $e) {
			echo "Query failure" . NL. $query;
			echo $e->getMessage();
		}
		return $max_id;
	}

  //15-09-2018
    public function uploadSelfImage($imageDirectory,$field,$newfilename){
      $temp = explode(".", $_FILES['regis_upload']["name"]);
      $newfilename = round(microtime(true)) . '.' . end($temp);
      // move_uploaded_file($_FILES["file"]["tmp_name"], "../img/imageDirectory/" . $newfilename);
      move_uploaded_file($_FILES['regis_upload']["tmp_name"], $imageDirectory . $newfilename);
      echo $_FILES['regis_upload']["tmp_name"];
      echo $imageDirectory;
    }

    public function uploadImage($uploadDir, $max_size, $array_image, $array_field)
    {
        $res = [];
        foreach ($array_field as $image) {

            if ($array_image[$image]['size'] < $max_size) {
                $imageinfo = getimagesize($array_image[$image]['tmp_name']);
                // echo $array_image[$image]['tmp_name']."<br/>";
                if ($imageinfo['mime'] == 'image/gif' || $imageinfo['mime'] == 'image/jpeg' || $imageinfo['mime'] == 'image/png' || $imageinfo['mime'] == 'image/jpg') {

                    if ($imageinfo['mime'] == 'image/jpg' || $imageinfo['mime'] == 'image/jpeg') {
                        $uploadedfile = $array_image[$image]['tmp_name'];
                        $src          = imagecreatefromjpeg($uploadedfile);
                    } else if ($imageinfo['mime'] == 'image/png') {
                        $uploadedfile = $array_image[$image]['tmp_name'];
                        $src          = imagecreatefrompng($uploadedfile);
                    } else {
                        $uploadedfile = $array_image[$image]['tmp_name'];
                        $src          = imagecreatefromgif($uploadedfile);
                    }
                    list($width, $height) = getimagesize($uploadedfile);

                    $newwidth  = 174;
                    $newheight = ($height / $width) * $newwidth;
                    $tmp       = imagecreatetruecolor($newwidth, $newheight);

                    imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                    $filename = $this->generateRandomString(5) . str_replace(" ", "", basename($array_image[$image]['name']));
                    $upload   = $uploadDir . "/" . $filename;
                  echo  $pro      = imagepng($tmp, $upload, 100) . "<br/>";

                    imagedestroy($src);
                    imagedestroy($tmp);

                    if ($pro == true) {
                        $res[$image]['filename'] = $filename;
                        $res[$image]['msg']      = 'Success';
                    } else {
                        $res[$image]['filename'] = '';
                        $res[$image]['msg']      = "File uploading failed.\n";
                    }
                } else {
                    $res[$image]['filename'] = '';
                    $res[$image]['msg']      = "Sorry, only .gif, .jpg and .png ! \n";
                }
            } else {
                $res[$image]['filename'] = '';
                $res[$image]['msg']      = "Sorry, File must be under 10 Mb ! \n" . $_FILES['gambar_1']['size'];
            }
        }
        // return $res[$image]['filename'];
        return $pro;
    }

  function getKwitansi($month,$year){
    $size_code = strlen($kode)+1;
    global $cfg;
    $objDB = new Database();
    try {
      $query="SELECT max(substr(no_kwitansi,-4)) as id FROM t_kwitansi where substr(no_kwitansi,3,4) = '".$year.$month."'";
      $data=$objDB->select($query);
      foreach($data as $item):
        if(!isset($item['id'])) {
          $maxid='KW'.$year.$month."0001";
            } else {
          $maxid='KW'.$year.$month.str_pad($item['id']+1,4, "0", STR_PAD_LEFT); 
        }
      endforeach;
    } catch (Exception $e) {
      echo "Query failure" . NL;
      echo $e->getMessage();
    }
    return $maxid;
  }
	function getNoTransID($kode,$primary_field,$table){
		$month=date("m");
		$year= date("Y");
		global $cfg;
		$objDB = new Database();
		try {
			//$refrance = 'MKE/WO/'.$month.'/'.$year.'';
			$query="SELECT max(substr($primary_field,-5,5)) as $primary_field FROM $table where substr($primary_field,5,7) = '".$month."/".$year."'";
			//call select methods to retreive max id Order 00001	 
			$data=$objDB->select($query);
			foreach($data as $item):
				$xid=$item[$primary_field];
				if(!isset($item[$primary_field])) {
					$maxid=$kode.'/'.$month."/".$year."/00001";
			    	} else {
					$maxid=$kode.'/'.$month."/".$year."/".str_pad($item[$primary_field]+1, 5, "0", STR_PAD_LEFT);	
				}
			endforeach;
		} catch (Exception $e) {
			echo "Query failure" . NL;
			echo $e->getMessage();
		}
		return $maxid;
	}
	function selGlobal($idx,$fldname,$primary_field,$name,$table) {
			$objDB = new Database();

			try {
				print "<select name=\"$fldname\" id=\"$fldname\" $xsubmit class=\"form-control select2\"  required>\n";
				print ("<option value=''>--Choose One--</option>\n");
				$data=$objDB->select("select * from $table");
				foreach($data as $item):
				$selc = "";
					if ($idx==$item[$primary_field]) $selc = " selected";
					print "<option value=\"".$item[$primary_field]."\" $selc>".stripslashes($item[$name])."</option>\n";
				endforeach;
				print "</select>\n";
			} catch (Exception $e) {
					echo $e->getMessage();
			}
	}
	function convert_number_to_words($number) {
    
		$hyphen      = ' ';
		$conjunction = ' ';
		$separator   = ' ';
		$negative    = 'Negative ';
		$decimal     = ' Point ';
		$dictionary  = array(
			0                   => 'Kosong',
			1                   => 'Satu',
			2                   => 'Dua',
			3                   => 'Tiga',
			4                   => 'Empat',
			5                   => 'Lima',
			6                   => 'Enam',
			7                   => 'Tujuh',
			8                   => 'Delapan',
			9                   => 'Sembilan',
			10                  => 'Sepuluh',
			11                  => 'Sebelas',
			12                  => 'Dua belas',
			13                  => 'Tiga belas',
			14                  => 'Empat Belas',
			15                  => 'Lima Belas',
			16                  => 'Enam Belas',
			17                  => 'Tujuh Belas',
			18                  => 'Delapan Belas',
			19                  => 'Sembilan Belas',
			20                  => 'Dua Puluh',
			30                  => 'Tiga Puluh',
			40                  => 'Empat Puluh',
			50                  => 'Lima Puluh',
			60                  => 'Enam Puluh',
			70                  => 'Tujuh Puluh',
			80                  => 'Delapan Puluh',
			90                  => 'Sembilan Puluh',
			100                 => 'Ratus',
			1000                => 'Ribu',
			1000000             => 'Juta',
			1000000000          => 'Miliar',
			1000000000000       => 'Triliun',
			1000000000000000    => 'Quadrillion',
			1000000000000000000 => 'Quintillion'
		);
		
		if (!is_numeric($number)) {
			return false;
		}
		
		if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
			// overflow
			trigger_error(
				'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
				E_USER_WARNING
			);
			return false;
		}

		if ($number < 0) {
			return $negative . $this->convert_number_to_words(abs($number));
		}
		
		$string = $fraction = null;
		
		if (strpos($number, '.') !== false) {
			list($number, $fraction) = explode('.', $number);
		}
		
		switch (true) {
			case $number < 21:
				$string = $dictionary[$number];
				break;
			case $number < 100:
				$tens   = ((int) ($number / 10)) * 10;
				$units  = $number % 10;
				$string = $dictionary[$tens];
				if ($units) {
					$string .= $hyphen . $dictionary[$units];
				}
				break;
			case $number < 1000:
				$hundreds  = $number / 100;
				$remainder = $number % 100;
				$string = $dictionary[$hundreds] . ' ' . $dictionary[100];
				if ($remainder) {
					$string .= $conjunction . $this->convert_number_to_words($remainder);
				}
				break;
			default:
				$baseUnit = pow(1000, floor(log($number, 1000)));
				$numBaseUnits = (int) ($number / $baseUnit);
				$remainder = $number % $baseUnit;
				$string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
				if ($remainder) {
					$string .= $remainder < 100 ? $conjunction : $separator;
					$string .= $this->convert_number_to_words($remainder);
				}
				break;
		}
		
		if (null !== $fraction && is_numeric($fraction)) {
			$string .= $decimal;
			$words = array();
			foreach (str_split((string) $fraction) as $number) {
				$words[] = $dictionary[$number];
			}
			$string .= implode(' ', $words);
		}
		
		return $string;
	}	
	function bitly($url,$format = 'xml',$version = '2.0.1'){
		$bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url). '&login=arifragil22&apiKey=R_8beaaae711824ce5b8c8e5825a4da07c&format='.$format;
		$response = file_get_contents($bitly);
		if(strtolower($format) == 'json') {
			$json = @json_decode($response,true);
		  	return $json['results'][$url]['shortUrl'];
		}else{ //xml
		  	$xml = simplexml_load_string($response);
			return 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
		}
	}
	public function encode($string){
        $encrypt_method = "AES-256-CBC";
        $secret_key = '4R1frAgiLPaMu17KAS';
        $secret_iv = '4R1frAgiLPaMu17KAS';
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
        return $output;
    }
    public function decode($string){
        $encrypt_method = "AES-256-CBC";
        $secret_key = '4R1frAgiLPaMu17KAS';
        $secret_iv = '4R1frAgiLPaMu17KAS';
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        return $output;
    }
    public function getAccessFile($menu_id)
    {
        global $cfg;
        $objDB = new Database();
        try {
            $query = "SELECT concat(view,'#',edit,'#',del) name FROM m_menu_level WHERE menu_id = '" . $menu_id . "' AND level_id = '" . $_SESSION['level_id'] . "'";
            //call select methods to retreive max id Order 00001
            $data = $this->getName($query);
        } catch (Exception $e) {
            echo "Query failure" . NL . $query;
            echo $e->getMessage();
        }
        return $data;
    }
}	
 ?>
