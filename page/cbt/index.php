<?php

include '../../config/conn.php';
include '../../helper/url.php';

session_start();
// cek apakah yang mengakses halaman ini sudah login
if ($_SESSION['level'] == "") {
    header("location:../../?pesan=belum_login");
} else if ($_SESSION['level'] != "user") {
    header("location:../../?pesan=forbidden");
}

$username = $_SESSION['username'];

$query = "SELECT * FROM tb_users_cbt
            INNER JOIN tb_users ON tb_users.id_users = tb_users_cbt.id_users
            INNER JOIN tb_users_address ON tb_users.id_users = tb_users_address.id_users
            INNER JOIN tb_users_utility ON tb_users.id_users = tb_users_utility.id_users
            INNER JOIN tb_users_status ON tb_users.id_users = tb_users_status.id_users
            INNER JOIN tb_test ON tb_users_cbt.test_id = tb_test.test_id
            WHERE tb_users_cbt.username = '$username'";
$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {

    include '../template/head.php';
    include '../template/sidebar.php';

    $ekskul1 = $row['id_ekstra_1'];
    $ekskul2 = $row['id_ekstra_2'];
    $sql = mysqli_query($conn, "SELECT * FROM tb_ekstrakurikuler 
                                            INNER JOIN tb_users ON tb_ekstrakurikuler.id_ekstra = tb_users.id_ekstra_1 
                                            WHERE tb_users.id_ekstra_1= '$ekskul1'");
    while ($data1 = mysqli_fetch_array($sql)) {
        $ekstra1 = $data1['ekstrakurikuler'];
    }
    $sql2 = mysqli_query($conn, "SELECT * FROM tb_ekstrakurikuler 
                                            INNER JOIN tb_users ON tb_ekstrakurikuler.id_ekstra = tb_users.id_ekstra_2
                                            WHERE tb_users.id_ekstra_2 = '$ekskul2'");
    while ($data2 = mysqli_fetch_array($sql2)) {
        $ekstra2 = $data2['ekstrakurikuler'];
    }
?>

<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Data Diri Peserta CBT</h5>
                <div class="row mt-5">
                    <div class="col-xl-9">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-md-right">Nomor Peserta</label>
                            <div class="col-sm-8">
                                <p class="mt-2 tx-medium">
                                    <?php echo $row['username']; ?>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-md-right">Nama Peserta</label>
                            <div class="col-sm-8">
                                <p class="mt-2 tx-medium">
                                    <?php echo $row['name']; ?>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-md-right">Tempat
                                Lahir</label>
                            <div class="col-sm-8">
                                <p class="mt-2 tx-medium">
                                    <?php echo $row['birth_place']; ?>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-md-right">Tanggal Lahir</label>
                            <div class="col-sm-8">
                                <p class="mt-2 tx-medium">
                                    <?php echo tgl_indo($row['birth_date']); ?>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-md-right">Jenis Kelamin</label>
                            <div class="col-sm-8">
                                <p class="mt-2 tx-medium">
                                    <?php if ($row['gender'] == 'L') { ?>
                                    LAKI-LAKI
                                    <?php } else if ($row['gender'] == 'P') { ?>
                                    PEREMPUAN
                                    <?php } ?> </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-md-right">Ekstrakurikuler Pertama</label>
                            <div class="col-sm-8">
                                <p class="mt-2 tx-medium">
                                    <?php echo $ekstra1 ?>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-md-right">Ekstrakurikuler Kedua</label>
                            <div class="col-sm-8">
                                <p class="mt-2 tx-medium">
                                    <?php echo $ekstra2 ?>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-md-right">Agenda Ujian</label>
                            <div class="col-sm-8">
                                <p class="mt-2 tx-medium">
                                    <?php echo $row['test_name'] ?>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-md-right">Tanggal Ujian</label>
                            <div class="col-sm-8">
                                <p class="mt-2 tx-medium">
                                    <?php echo tgl_indo($row['users_cbt_date']) ?>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-md-right">Waktu Ujian</label>
                            <div class="col-sm-8">
                                <p class="mt-2 tx-medium">
                                    <?php echo $row['cbt_time_start'] . '~' . $row['cbt_time_end'] ?>
                                </p>
                            </div>
                        </div>

                    </div>
                    <div class="col-xl-3">
                        <div class="image text-center">
                            <?php if ($row['foto_users'] == NULL) { ?>
                            <img src="<?php echo $urlSpekta . 'assets/img/logo SS.png' ?>" alt="img user" width="200">
                            <?php } else { ?>
                            <img src="<?php echo $urlSpekta . 'assets/img/user/' . $row['foto_users']; ?>"
                                alt="img user" width="200">
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php }
include '../template/script.php'; ?>

</body>

</html>