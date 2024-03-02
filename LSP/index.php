<?php
    // koneksi DataBase
    $server = "localhost";
    $user = "root";
    $pass = "";
    $database = "dbbuku";

    $koneksi = mysqli_connect($server, $user, $pass, $database)or die(mysqli_error($koneksi));
    // jika diclik simpan
    if(isset($_POST['bsimpan'])){

        if($_GET['hal'] == "edit"){
            // data akan diedit
            $edit = mysqli_query($koneksi, "UPDATE book set judul_buku ='$_POST[tjudul]',pengarang ='$_POST[tngarang]',penerbit ='$_POST[tterbit]'
            WHERE id_buku = '$_GET[id]' ");

            // jikas simpan sukses
            if($edit){  
                echo "<script>
                    alert('Data Berhasil di EDIT!');
                    document.location='index.php';
                </script>";
            }
            else{
                echo "<script>
                alert('EDIT data GAGAL!');
                document.location='index.php';
                </script>"; 
            }
        }
        else{
            // data akan disimpan baru
            $nama = $_FILES['tgambar']['name'];
            $x = explode('.', $nama);
            $ekstensi = end($x);
            $file_tmp = $_FILES['tgambar']['tmp_name'];
            move_uploaded_file($file_tmp, 'aset/'.$nama);
            $simpan = mysqli_query($koneksi, "INSERT INTO book (judul_buku, gambar, pengarang, penerbit, persediaan)
            VALUE ('$_POST[tjudul]', '$nama', '$_POST[tngarang]', '$_POST[tterbit]',  '$_POST[tsedia]') ");

            // jikas simpan sukses
            if($simpan){  
                echo "<script>
                    alert('Data Berhasil di SIMPAN!');
                    document.location='index.php';
                </script>";
            }
            else{
                echo "<script>
                alert('Simpan data GAGAL!');
                document.location='index.php';
                </script>"; 
            }
        }

        
    }

    // pengujian jika tombol Edit / Hapus di klik
    if(isset($_GET['hal'])){
        // pengujian data yang akan di edit
        if($_GET['hal'] == "edit"){
            $tampil = mysqli_query($koneksi,"SELECT *FROM book WHERE id_buku = '$_GET[id]' ");
            $data = mysqli_fetch_array($tampil);

            if($data){
                // jika data di temukan, maka data ditampung kedalam variable
                $vjudul = $data['judul_buku'];
                $vpengarang = $data['pengarang'];
                $vpenerbit = $data['penerbit'];                
            }
        }
        
        else if ($_GET['hal'] == "hapus"){
            // persiapan hapus data
            $hapus = mysqli_query($koneksi, "DELETE FROM book WHERE id_buku = '$_GET[id]' ");
            if($hapus){
                echo "<script>
                alert('Data Berhasil di HAPUS!');
                document.location='index.php';
                </script>";
            }
        }
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LSP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
    <div class="container">
    <h1 class="text-center mb-4">FORM INPUT DATA BUKU</h1>
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                <h4>Edit Buku</h4>
            </div>
            <div class="card-body">
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="">Judul Buku</label>
                        <input type="text" name="tjudul"  value="<?=@$vjudul?>" class="form-control" placeholder="Masukkan Judul Buku Anda!">
                    </div>
                    <div class="form-group  mt-3">
                        <label for="">Pilih Foto Buku</label>
                        <input type="file" name="tgambar"  class="form-control">
                    </div>
                    <div class="form-group  mt-3">
                        <label for="">Pengarang Buku</label>
                        <input type="text" name="tngarang"  value="<?=@$vpengarang?>" class="form-control" placeholder="Masukkan Pengarang Buku Anda!">
                    </div>
                    <div class="form-group  mt-3">
                        <label for="">Penerbit Buku</label>
                        <input type="text" name="tterbit"  value="<?=@$vpenerbit?>" class="form-control" placeholder="Masukkan Penerbit Buku Anda!">
                    </div>
                    <div class="form-group  mt-3">
                        <label for="">Persediaan Buku</label>
                        <input type="text" name="tsedia"  class="form-control" placeholder="Masukkan Jumlah Persediaan Buku Anda!">
                    </div>
                    <br/>
                        <button type="submit" class="btn btn-success" type="button" name="bsimpan">Simpan</button>
                </form>                
            </div>
        </div>
        <br/>
        <div class="card">
            <div class="card-header bg-success text-white text-center">
                <h4>Daftar Buku</h4>
            </div>
            <nav class="navbar navbar-light  mt-2">
                <div class="container-fluid">
                    <button class="btn btn-success">Tambah</button> 
                    <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
            </nav>
            <div class="card-body d-flex">
                <div class="d-flex flex-wrap" style="column-gap: 20px; row-gap: 20px">

                    <?php
                        $no =1;
                        $tampil = mysqli_query($koneksi, "SELECT * fROM book order by id_buku desc");
                        while($data = mysqli_fetch_array($tampil)):
                    ?>

                    <div class="card p-4" style="width: 18rem;">
                    <div class="text-center">
                        <?php echo "<img src='aset/$data[gambar]' width='200' height='300'/>";?>
                    </div>
                        <p class="card-text mt-4">Judul:           <?=$data['judul_buku']?></p>
                        <p class="card-text mt-2">Pengarang:       <?=$data['pengarang']?></p>
                        <p class="card-text mt-2">Penerbit:        <?=$data['penerbit']?></p>
                        <p class="card-text mt-2">Persediaan:      <?=$data['persediaan']?></p>
                        <hr/>
                        <div class="card-body">
                            <a href="index.php?hal=edit&id=<?=$data['id_buku']?>" class="btn btn-warning">Edit</a>
                            <a href="index.php?hal=hapus&id=<?=$data['id_buku']?>" onclick="return confirm('Apakah Anda Yakin Ingin Meng-HAPUS Data Ini ?')" class="btn btn-danger">Hapus</a>
                        </div>
                    </div>

                    <?php endwhile;?>

                </div>        
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>