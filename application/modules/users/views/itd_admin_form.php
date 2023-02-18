<div class="box box-default">
    <form enctype="multipart/form-data" method="POST" id="user_form" action="<?php echo site_url('users/update_itd_admin_email'); ?>">

        <div class="box-header with-border">
            <h3 class="box-title">
                Add ITD ADMIN EMAIL
            </h3>
            <button type="submit" class="btn btn-primary pull-right">Save</button>
            <?php if (isset($mode) && $mode != 'update') { ?>
                <a class="pull-right btn btn-secondary text-white" onclick="history.back()" href="javascript:void(0);">BACK</a>
            <?php } ?>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-12">
                    <div class="col-6">
                        <div class="form-group row">
                            <label for="email_id" class="col-sm-4 col-form-label">Email ID<span class="required">*</span>(USE COMMA FOR MULTIPLE EMAIL ID)</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="admin_email" required><?php echo isset($result['admin_id']) ? $result['admin_id'] : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary pull-right">Save</button>
                            <?php if (isset($mode) && $mode != 'update') { ?>
                                <a class="pull-right btn btn-secondary text-white" onclick="history.back()" href="javascript:void(0);">BACK</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>