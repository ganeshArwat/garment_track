  <!-- /.box-header -->
  <div class="box-body">

      <p><strong>TRACKING NO:</strong> <?php echo $awb_no; ?></p>
      <p><strong>ORIGIN:</strong> <?php echo $origin_name; ?></p>
      <p><strong>DESTINATION:</strong> <?php echo $dest_name; ?></p>
      <p><strong>SERVICE:</strong> <?php echo $service_name; ?></p>

      <?php if (isset($contract) && is_array($contract) && count($contract) > 0) { ?>
          <div class="row">
              <div class="col-6">
                  <table class="table table-bordered table-striped table-responsive">
                      <tr class="section_head">
                          <th>Sr.No</th>
                          <th>Vendor Name</th>
                          <th>Vendor Code</th>
                          <th>Amount</th>
                          <th>TAT</th>
                      </tr>
                      <?php
                        $level = 1;

                        foreach ($contract[0] as $key => $value) {
                        ?>
                          <tr>
                              <td>L<?php echo $level; ?></td>
                              <td><?php echo isset($all_vendor[$key]) ? $all_vendor[$key]['name'] : ''; ?></td>
                              <td><?php echo isset($all_vendor[$key]) ? $all_vendor[$key]['code'] : ''; ?></td>
                              <td><?php echo $value; ?></td>
                              <td><?php echo isset($contract_tat[$key]) ? $contract_tat[$key] : ''; ?></td>
                          </tr>
                      <?php
                            $level++;
                        }
                        ?>
                  </table>
              </div>
          </div>
      <?php } else { ?>
          <h5>NO CONTRACT FOUND</h5>
      <?php   } ?>
  </div>