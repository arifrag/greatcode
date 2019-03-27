<?php
error_reporting('~Notice');
session_start();
$target = 1;
require_once "controllers/class.CtrlGlobal.php";
$objCtrl = new CtrlGlobal();
//Params Global
if ($_GET['act'] == "") {
    $act = $_POST['act'];
} else {
    $act = $_GET['act'];
}

if ($_GET['id'] == "") {
    $id = $_POST['id'];
} else {
    $id = $_GET['id'];
}
if ($_GET['xid'] == "") {
    $xid = $_POST['xid'];
} else {
    $xid = $_GET['xid'];
}
    switch ($act) {
      case 'cekSession':
        if($_SESSION['id_member'] != ""){
          // $sql = "SELECT full_name as name FROM member WHERE id_member = '".$_SESSION['id_member']."'";
          // echo $objCtrl->getName($sql);
          $full_name = $_SESSION['full_name'];
          $foto = $_SESSION['foto'];
          echo "4@".$full_name."@".$foto;
        }else{
          echo "Unknown";
        }
        break;
      case 'labsDataRecommendation':
        $sql = "SELECT l.id_labs,l.labs_name, l.author, l.image_thumbnail,
              (SELECT count(id) FROM a_view v WHERE l.id_labs = v.id_labs) as view,
              (SELECT count(id) FROM a_coffea c WHERE l.id_labs = c.id_labs) as coffea,
              (SELECT count(id) FROM a_download d WHERE l.id_labs = d.id_labs) as download
              FROM labs l  WHERE id_labs != '".$xid."'";
        $row = $objCtrl->GetGlobalFilter($sql);
        echo json_encode($row);
        break;
      case 'labsData':
          $sql = "SELECT l.id_labs, l.labs_name, l.author, l.description, l.image_thumbnail, (SELECT count(v.id_labs) FROM a_view v WHERE v.id_labs=l.id_labs) as view,(SELECT count(d.id)  FROM a_download d WHERE d.id_labs=l.id_labs)  as download,
          (SELECT count(c.id_labs) FROM a_coffea c WHERE c.id_labs = l.id_labs AND id_member = '".$_SESSION['id_member']."') as statusCoffea,
          (SELECT count(c.id_labs) FROM a_coffea c WHERE c.id_labs = l.id_labs) as coffea
          FROM labs l";
          $row = $objCtrl->GetGlobalFilter($sql);
          $array_row = [];
          foreach ($row as $item) {
            if($item['statusCoffea'] == 1){
              $item['status'] = 'like';
            }else{
              $item['status'] = 'unlike';
            }
            array_push($array_row, $item);
          }
          echo json_encode($array_row);
      break;
      case 'labsDataProfile':
        $sql = "SELECT l.id_labs, l.labs_name, l.author, l.description, l.image_thumbnail, 
          (SELECT count(v.id_labs) FROM a_view v WHERE v.id_labs=l.id_labs) as view,
          (SELECT count(d.id)  FROM a_download d WHERE d.id_labs=l.id_labs)  as download,
          (SELECT count(c.id_labs) FROM a_coffea c WHERE c.id_labs = l.id_labs AND id_member = '".$_SESSION['id_member']."') as statusCoffea,
          (SELECT count(c.id_labs) FROM a_coffea c WHERE c.id_labs = l.id_labs) as coffea
          FROM labs l WHERE id_member = '".$_SESSION['id_member']."'";
          $row = $objCtrl->GetGlobalFilter($sql);
          $array_row = [];
          foreach ($row as $item) {
            if($item['statusCoffea'] == 1){
              $item['status'] = 'like';
            }else{
              $item['status'] = 'unlike';
            }
            array_push($array_row, $item);
          }
          echo json_encode($array_row);
        break;
      case 'labsDataDetailImage':
          $sql = "SELECT ld.image
          FROM labs_detail ld
          WHERE ld.id_labs = '".$xid."'";
          $row = $objCtrl->GetGlobalFilter($sql);
          echo json_encode($row);
      break;
      case 'labsDataDetailDesc':
          $sql = "SELECT l.labs_name, l.author, l.description, l.image_thumbnail,date_format(l.log_time,'%Y-%m-%d') as timeago,
          (SELECT count(v.id) FROM a_view v WHERE v.id_labs = l.id_labs) as count, 
          (SELECT count(d.id) FROM a_download d WHERE d.id_labs = l.id_labs) as download,
          (SELECT count(c.id_labs) FROM a_coffea c WHERE c.id_labs = l.id_labs AND id_member = '".$_SESSION['id_member']."') as statusCoffea,
          (SELECT count(c.id_labs) FROM a_coffea c WHERE c.id_labs = l.id_labs) as coffea
          FROM labs l
          WHERE l.id_labs = '".$xid."'";
          $row = $objCtrl->GetGlobalFilter($sql);
          echo json_encode($row);
      break;
      case 'labsDataProgrammingIcon':
        $sql = "SELECT icon, icon_name FROM labs_programming_icon WHERE id_labs = '".$xid."'";
        $row = $objCtrl->GetGlobalFilter($sql);
        echo json_encode($row);
        break;
      case 'labsDataComment':
        if($_SESSION['id_member'] != ""){
          $sql = "SELECT m.full_name, c.comment FROM member m, a_comment c
                  WHERE m.id_member = c.id_member AND c.id_labs = '".$xid."'";
          $row = $objCtrl->GetGlobalFilter($sql);
          echo json_encode($row);
        }
      break;
      case 'plusCoffea':
        if($_SESSION['id_member'] != ""){
          $sql = "SELECT id_labs as name FROM a_coffea WHERE id_labs = '".$xid."' AND id_member = '".$_SESSION['id_member']."'";
          $id_labs = $objCtrl->getName($sql);
          if($id_labs ==  ""){
            $objCtrl->insert('a_coffea',array(
              'id_member' => $_SESSION['id_member'],
              'id_labs' => $xid,
            ));
          }else{
            $objCtrl->delete('a_coffea',array(
              'id_member' => $_SESSION['id_member'],
              'id_labs' => $xid,
            ));
          }
        }else{
          echo "Unknown";
        }
      break;
      case 'plusView':
        if($_SESSION['id_member'] != ""){
          $objCtrl->insert('a_view',array(
            'id_member' => $_SESSION['id_member'],
            'id_labs' => $xid,
          ));
        }
      break;
      case 'plusDownload':
        if($_SESSION['id_member'] != ""){
          $objCtrl->insert('a_download',array(
            'id_member' => $_SESSION['id_member'],
            'id_labs' => $xid,
          ));
        }
      break;
      case 'Login':
        if(stripos($_GET['username'],'@') !== false){
          $sql = "SELECT concat(id_member,'#',full_name,'#',username,'#',no_hp,'#',foto,'#',password,'#',status) as name FROM member WHERE email = '" . $_GET['username'] . "'";
        }else{
          $sql = "SELECT concat(id_member,'#',full_name,'#',username,'#',no_hp,'#',foto,'#',password,'#',status) as name FROM member WHERE username = '" . $_GET['username'] . "'";
        }
        list($id_member,$full_name,$username,$no_hp,$foto,$password,$status) = explode('#', $objCtrl->getName($sql));
        if ($password != "") {
            if (password_verify($_GET['pass'], $password)) {
                session_start();
                $_SESSION['id_member'] = $id_member;
                $_SESSION['full_name'] = $full_name;
                $_SESSION['username'] = $username;
                $_SESSION['no_hp'] = $no_hp;
                $_SESSION['foto'] = $foto;
                echo "4@".$full_name."@".$foto; //Berhasil masuk
            } else {
                echo "1"; //Password Salah
            }
        } else if ($status == "1") {
            echo "2"; //Belum terverifikasi
        } else if ($status == "") {
            echo "3"; //Belum Terdaftar
        }
        break;
      case 'Logout':
        unset($_SESSION['id_member']);
        unset($_SESSION['full_name']);
        unset($_SESSION['username']);
        unset($_SESSION['no_hp']);
        unset($_SESSION['foto']);
        session_destroy();
        break;
      case 'Register':
        if($_SESSION['id_member'] == ""){
          $sql = "SELECT id_member as name FROM member WHERE username = '".$_POST['regis_username']."' OR email = '".$_POST['regis_email']."'";
          $id_member = $objCtrl->getName($sql);
          if($id_member != ""){
            echo "1";// "Maaf email / Username sudah terpakai !";
          }else{
            $id_member = $objCtrl->getGlobalID('M','id_member','member');
            $objCtrl->insert('member',array(
              'id_member' => $id_member,
              'full_name' => $_POST['regis_full_name'],
              'username' => $_POST['regis_username'],
              'email' => $_POST['regis_email'],
              'no_hp' => $_POST['regis_no_hp'],
              'foto' => $_POST['regis_foto'],
              'password' => password_hash($_POST['regis_password'], PASSWORD_DEFAULT),
            ));
            $uploadDir = '../assets/member/images/';//path directory berdasarkan server
            if ($_FILES['regis_upload' . $i]['name'] != '') {

                if ($_FILES['regis_upload' . $i]['size'] < 1024 * 1024) {
                    $imageinfo = getimagesize($_FILES['regis_upload' . $i]['tmp_name']);

                    if ($imageinfo['mime'] == 'image/gif' || $imageinfo['mime'] == 'image/jpeg' || $imageinfo['mime'] == 'image/png' || $imageinfo['mime'] == 'image/jpg') {

                        if ($imageinfo['mime'] == 'image/jpg' || $imageinfo['mime'] == 'image/jpeg') {
                            $uploadedfile = $_FILES['regis_upload' . $i]['tmp_name'];
                            $src          = imagecreatefromjpeg($uploadedfile);
                        } else if ($imageinfo['mime'] == 'image/png') {
                            $uploadedfile = $_FILES['regis_upload' . $i]['tmp_name'];
                            $src          = imagecreatefrompng($uploadedfile);
                        } else {
                            $uploadedfile = $_FILES['regis_upload' . $i]['tmp_name'];
                            $src          = imagecreatefromgif($uploadedfile);
                        }
                        list($width, $height) = getimagesize($uploadedfile);

                        $newwidth  = 174;
                        $newheight = ($height / $width) * $newwidth;
                        $tmp       = imagecreatetruecolor($newwidth, $newheight);

                        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                        // $filename = $uploadDir . str_replace(" ", "", basename($_FILES['regis_upload' . $i]['name']));
                        $ext = explode('/',$imageinfo['mime'])[1];

                        $newfilename =  $id_member.'.'.$ext;

                        $gambar = str_replace(" ", "", basename($_FILES['regis_upload' . $i]['name']));

                        $pro = imagejpeg($tmp, $uploadDir.$newfilename, 100);

                        // imagedestroy($src);
                        // imagedestroy($tmp);

                        if ($pro == true) {
                            $msg2 = "File is valid, and uploaded.\n";
                            // $objCtrl->insert('m_item_image', array(
                            //     'gambar'     => $gambar,
                            //     'image_desc' => $_POST['image_desc_' . $i],
                            //     'id_item'    => $max_id));
                        } else {
                            $msg2 = "File uploading failed.\n".$filename;
                        }
                    } else {
                        $msg2 = "Sorry, only .gif, .jpg and .png ! \n";
                    }
                } else {
                    $msg2 = "Sorry, File must be under 10 Mb ! \n" . $_FILES['regis_upload']['size'];
                }
                echo $newfilename;
            }

            session_start();
            $_SESSION['id_member'] = $id_member;
            $_SESSION['full_name'] = $_POST['regis_full_name'];
            $_SESSION['username'] = $_POST['regis_username'];
            $_SESSION['no_hp'] = $_POST['regis_no_hp'];
            $_SESSION['foto'] = $newfilename;
          }
        }
        break;
    }
