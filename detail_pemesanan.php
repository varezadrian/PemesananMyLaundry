<?= $this->extend('layout/template_admin'); ?>

<?= $this->section('content'); ?>
<div class="content">
    <div class="page-inner">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-9">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="page-pretitle">
                            Detail Pemesanan
                        </h6>
                        <h4 class="page-title">Kode Pemesanan #<?= $pengajuan->IDPemesanan; ?></h4>
                    </div>
                    <div class="col-auto">
                        <a href="/pembayaran" class="btn btn-primary ml-2">
                            <span><i class="fas fa-arrow-left"></i></span> Kembali
                        </a>
                    </div>
                </div>
                <div class="page-divider"></div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-invoice">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 info-invoice">
                                        <h6 class="sub">Tanggal Pemesanan</h6>
                                        <p><b><?= date('d M Y H:i:s', strtotime($pengajuan->TanggalPemesanan)); ?></b></p>
                                    </div>
                                    <div class="col-md-3 info-invoice">
                                        <h6 class="sub">Max Waktu Pembayaran</h6>
                                        <p><b><?= date('d M Y H:i:s', strtotime($pengajuan->maxPembayaran)); ?></b></p>
                                    </div>
                                    <div class="col-md-3 info-invoice">
                                        <h6 class="sub">Status Pembayaran</h6>
                                        <p><b><?= $pengajuan->Status_Pembayaran; ?></b></p>
                                    </div>
                                    <div class="col-md-3 info-invoice">
                                        <h6 class="sub">Status Pemesanan</h6>
                                        <p>
                                            <?php
                                            $stat = $pengajuan->Status;
                                            if ($stat === "PENDING") {
                                                $view = '<button type="button" class="btn btn-primary btn-sm"><b>PENDING</b></button>';
                                            } else if ($stat === "PROSES") {
                                                $view = '<button type="button" class="btn btn-warning btn-sm"><b>PROSES</b></button>';
                                            } else if ($stat === "SELESAI") {
                                                $view = '<button type="button" class="btn btn-success btn-sm"><b>SELESAI</b></button>';
                                            } else {
                                                $view = '<button type="button" class="btn btn-danger btn-sm"><b>DIBATALKAN</b></button>';
                                            }
                                            echo $view;
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="invoice-detail">
                                            <div class="invoice-top">
                                                <h3 class="title"><strong>Detail Pemesan</strong></h3>
                                            </div>
                                            <div class="invoice-item">
                                                <div class="card card-with-nav">
                                                    <div class="card-body">
                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <div class="form-group form-group-default">
                                                                    <label>Nama Pemesan</label>
                                                                    <input type="text" class="form-control" name="name" placeholder="Name" value="<?= $pengajuan->NamaPemesan; ?>" readonly style="opacity: 1!important;">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group form-group-default">
                                                                    <label>Nomor HP</label>
                                                                    <input type="text" class="form-control" name="name" placeholder="Name" value="<?= $pengajuan->NomorTlp; ?>" readonly style="opacity: 1!important;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <div class="form-group form-group-default">
                                                                    <label>Berat Pakaian</label>
                                                                    <input type="text" class="form-control" name="name" placeholder="Name" value="<?= $pengajuan->BeratPakaian; ?>" readonly style="opacity: 1!important;">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group form-group-default">
                                                                    <label>Tipe Pakaian</label>
                                                                    <input type="text" class="form-control" name="name" placeholder="Name" value="<?= $pengajuan->TipePakaian; ?>" readonly style="opacity: 1!important;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <div class="form-group form-group-default">
                                                                    <label>Waktu Pengerjaan</label>
                                                                    <?php
                                                                    $pengerjaan = $pengajuan->HariPengerjaan;
                                                                    if ($pengerjaan === "6") {
                                                                        $view = '<button type="button" class="btn btn-success btn-sm"><b>EXPRESS (6 JAM)</b></button>';
                                                                    } else if ($pengerjaan === "48") {
                                                                        $view = '<button type="button" class="btn btn-warning btn-sm"><b>CEPAT (2 Hari)</b></button>';
                                                                    } else if ($pengerjaan === "72") {
                                                                        $view = '<button type="button" class="btn btn-danger btn-sm"><b>NORMAL (3 Hari)</b></button>';
                                                                    } else {
                                                                        $view = '<button type="button" class="btn btn-danger btn-sm"><b>ERROR</b></button>';
                                                                    }
                                                                    echo $view;
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group form-group-default">
                                                                    <label>Delivery</label>
                                                                    <?php
                                                                    $pengerjaan = $pengajuan->HargaDelivery;
                                                                    if ($pengerjaan === "5000") {
                                                                        $view = '<button type="button" class="btn btn-info btn-sm"><b>Jemput</b></button>';
                                                                    } else if ($pengerjaan === "7000") {
                                                                        $view = '<button type="button" class="btn btn-info btn-sm"><b>Antar</b></button>';
                                                                    } else if ($pengerjaan === "10000") {
                                                                        $view = '<button type="button" class="btn btn-success btn-sm"><b>Antar - Jemput </b></button>';
                                                                    } else {
                                                                        $view = '<button type="button" class="btn btn-danger btn-sm"><b>ERROR</b></button>';
                                                                    }
                                                                    echo $view;
                                                                    ?>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-12">
                                                                <div class="form-group form-group-default">
                                                                    <label>Total Harga</label>
                                                                    <input type="text" class="form-control" name="name" placeholder="Name" value="Rp. <?= $pengajuan->Jumlah; ?>" readonly style="opacity: 1!important;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="separator-solid mb-3"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->include('layout/footer'); ?>
<?= $this->endSection(); ?>