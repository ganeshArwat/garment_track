<?php $session_data = $this->session->userdata('admin_user'); ?>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">
            WELCOME BACK, <?php echo $session_data['name']; ?>
        </h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-12">
                <?php
                if (isset($offer_data) && is_array($offer_data) && count($offer_data) > 0) { ?>
                    <table class="table table-sm table-hover table-bordered" style="border: 1px solid #ccc;">
                        <thead style="margin-bottom: 6px !important;background: #284d73;color: #fff;">
                            <tr>
                                <th class="title my-table-header" style="width: 25%;">TITLE</th>
                                <th class="title my-table-header" style="width: 75%;">SUBJECT</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($offer_data as $key => $value) { ?>
                                <tr>
                                    <td><?php echo $value['title']; ?></td>
                                    <td><?php echo $value['subject']; ?></td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                <?php }
                ?>
            </div>
        </div>
    </div>
</div>