<div id="center">
  <div class="container">
    <div class="row">
      <div class="content col-sm-12">
        <div class="customtab">
          <h3 class="productblock-title">Silahkan isi formulir berikut</h3>
        </div>
        <!-- .... -->
        <!-- tab-furniture-->
        <div class="tab-content">
          <div class="flash-data-error" data-flashdata="<?php echo $this->session->flashdata('error'); ?>"></div>
          <div class="flash-data" data-flashdata="<?php echo $this->session->flashdata('flash'); ?>"></div>
          <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
              <form action="" method="post">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="form-group">
                  <label>Nama Penyumbang</label>
                  <input type="text" class="form-control" name="nama" value="<?php echo set_value('nama'); ?>" autofocus>
                  <small class="text-danger"><?php echo form_error('nama'); ?></small>
                </div>
                <div class="form-group">
                  <label>Email</label>
                  <input type="text" class="form-control" name="email" value="<?php echo set_value('email'); ?>">
                  <small class="text-danger"><?php echo form_error('email'); ?></small>
                </div>
                <div class="form-group">
                  <label>Telepon / WhatsApp</label>
                  <input type="text" class="form-control" name="telp" value="<?php echo set_value('telp'); ?>">
                  <small class="text-danger"><?php echo form_error('telp'); ?></small>
                </div>
                <div class="form-group">
                  <label>Jumlah Buku</label>
                  <input type="text" class="form-control" name="jml" placeholder="Contoh: 14 Buku" value="<?php echo set_value('jml'); ?>">
                  <small class="text-danger"><?php echo form_error('jml'); ?></small>
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
            <div class="col-md-3"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>