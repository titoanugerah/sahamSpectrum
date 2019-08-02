<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <form method="post">
          <button class="btn btn-info" data-toggle="modal" data-target="#myModal1" hidden>Pencarian</button>
          <button class="btn btn-success" data-toggle="modal" data-target="#myModal">Tambah Saham Baru</button>

          <button type="submit" name="refreshPrediction" value="refreshPrediction" class="btn btn-success">Refresh Prediction</button>
        </form>
        <table class="table">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Company</th>
              <th class="text-center">Stock</th>
              <th class="text-center">Prediction</th>
              <th class="text-center">Options</th>
            </tr>

          </thead>
          <tbody>
            <?php $i=1;foreach ($content['stock'] as $item): ?>

            <tr>
              <td class="text-center"><?php echo $i; ?></td>
              <td class="text-center"><?php echo $item->stock_name; ?></td>
              <td class="text-center"><?php echo $item->stock_code; ?></td>
              <td class="text-center"><?php echo $item->prediction; ?></td>
              <td class="text-center"> <a href="<?php echo base_url('deleteStock/'.$item->id); ?>" class="btn btn-danger">Delete</a> </td>
            </tr>
          <?php $i++;endforeach; ?>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form  method="post" enctype="multipart/form-data">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambahkan Dokumen Baru</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">

            <div class="form-group col-md-12">
              <input type="text" name="stock_name" class="form-control" placeholder="Masukan nama perusahaan" value="">
            </div>

            <div class="form-group col-md-7">
              <input type="text" name="stock_type" class="form-control" placeholder="Masukan tipe perusahaan" value="">
            </div>

            <div class="form-group col-md-5">
              <input type="text" name="stock_code" class="form-control" placeholder="Masukan kode saham" value="">
            </div>

            <div class="file-field col-md-12">
              <div class="btn btn-primary btn-sm float-left">
                <span>Choose file</span>
                <input type="file" name="fileUpload">
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer modal-danger">
          <button type="submit" class="btn btn-warning" name="addStock" value="addStock">Tambah Saham</button>
          <button type="button" class="btn btn-grey" data-dismiss="modal">Kembali</button>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form  method="post" >
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Pencarian Saham</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Kode Saham</label>
            <input type="text" name="stock" class="form-control" placeholder="Masukan Kode Saham" value="">
          </div>
        </div>

        <div class="modal-footer modal-danger">
          <button type="submit" class="btn btn-warning" name="find" value="find">Cari</button>
          <button type="button" class="btn btn-grey" data-dismiss="modal">Kembali</button>
        </div>
      </div>
    </form>
  </div>
</div>
