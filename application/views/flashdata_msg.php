<div class="row feedback_msg">
    <div class="col-sm-12 com-md-12">
        <?php if ($this->session->flashdata('add_feedback') && $this->session->flashdata('add_feedback') != '') { ?>
            <div class="flash">
                <span><b><?php echo $this->session->flashdata('add_feedback'); ?></b></span>
                <span><a href="#" onclick="$('.flash').hide();">Close</a></span>
            </div>
        <?php
            $this->session->set_flashdata('add_feedback', '');
        }

        if ($this->session->flashdata('update_feedback') && $this->session->flashdata('update_feedback') != '') {
        ?>
            <div class="flash">
                <span><b><?php echo $this->session->flashdata('update_feedback'); ?></b></span>
                <span><a href="#" onclick="$('.flash').hide();">Close</a></span>
            </div>
        <?php
            $this->session->set_flashdata('update_feedback', '');
        }

        if ($this->session->flashdata('delete_feedback') && $this->session->flashdata('delete_feedback') != '') { ?>
            <div class="flash">
                <span><b><?php echo $this->session->flashdata('delete_feedback'); ?></b></span>
                <span><a href="#" onclick="$('.flash').hide();">Close</a></span>
            </div>
        <?php
            $this->session->set_flashdata('delete_feedback', '');
        }

        if ($this->session->flashdata('error_feedback') && $this->session->flashdata('error_feedback') != '') { ?>
            <div class="flash_error">
                <span><b><?php echo $this->session->flashdata('error_feedback'); ?></b></span>
                <span><a href="#" onclick="$('.flash').hide();">Close</a></span>
            </div>
        <?php
            $this->session->set_flashdata('error_feedback', '');
        } ?>

        <div class="flash" id="feedback_flash" style="display: none;">
            <span id="feedback_flash_msg"><b></b></span>
            <span><a href="#" onclick="$('.flash').hide();">Close</a></span>
        </div>
    </div>
</div>