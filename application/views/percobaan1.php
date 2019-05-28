<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <form  method="post">
          <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal1">Pencarian</button>
        </form>

        <table class="table">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Date</th>
              <th class="text-center">Open</th>
              <th class="text-center">high</th>
              <th class="text-center">low</th>
              <th class="text-center">close</th>
              <th class="text-center">volume</th>
            </tr>
          </thead>
          <tbody>
            <?php for ($i=1; $i <$content['list']['count']; $i++) {

              echo "<tr>";
              echo '<td class="text-center">'.$i.'</td>';
              echo '<td class="text-center">'.$content['list']['row'][$i][0].'</td>';
              echo '<td class="text-center">'.$content['list']['row'][$i][1].'</td>';
              echo '<td class="text-center">'.$content['list']['row'][$i][2].'</td>';
              echo '<td class="text-center">'.$content['list']['row'][$i][3].'</td>';
              echo '<td class="text-center">'.$content['list']['row'][$i][4].'</td>';
              echo '<td class="text-center">'.$content['list']['row'][$i][5].'</td>';
              echo "</tr>";

             }  ?>
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
            <div class="form-group">
              <label>Nama Dokumen</label>
              <input type="text" name="document_name" class="form-control" placeholder="Masukan nama file" value="">
            </div>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Informasi</label>
              <input type="text" name="document_info" class="form-control" placeholder="Masukan Informasi" value="">
            </div>
          </div>
          <div class="modal-body">

            <div class="md-form">
              <div class="file-field">
                <div class="btn btn-primary btn-sm float-left">
                  <span>Choose file</span>
                  <input type="file" name="fileUpload">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer modal-danger">
            <button type="submit" class="btn btn-warning" name="uploadFile" value="uploadFile">Upload</button>
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
            <button type="submit" class="btn btn-success" name="downloadStock" value="downloadStock">Download CSV</button>
            <button type="button" class="btn btn-grey" data-dismiss="modal">Kembali</button>
          </div>
        </div>
      </form>
    </div>
  </div>
