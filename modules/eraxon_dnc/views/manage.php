<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                  <div class="panel-body">
                    <div class="row">
                      <div class="col-md-6 mt-5">
                        <h4><?php echo '<i class=" fa fa-clipboard"></i> DNC Checker' ?></h4>
                      </div> 
                    </div>
                    <div class="clearfix"></div>
                    <div class="row">
                        <?php echo form_open(admin_url('eraxon_dnc/get_dnc')); ?>

                        <div class="col-md-12">
                             <div class="form-group">
                                <label for="verify_number" class="control-label">Enter Numbers to Verify</label>
                                <input type="number" name="verify_number" class="form-control">
                                </div>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">Verify</button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>

                    <div class="panel-body">
                        <div class="row">
                             <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td>Number</td>
                                            <td>Result</td>
                                        </tr>
                                    </thead>
                                    <tbody id="dnc_result">

                                    </tbody>
                                </table>
                             </div>
                        </div>
                    </div>

                </div>
            </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
$(function() 
{
    appValidateForm($('form'), {
        verify_number: 'required',
    }, manage_dnc_form);
    
    function manage_dnc_form(form) 
    {
        var data = $(form).serialize();
        var url = form.action;
        $.post(url, data).done(function(response) {
            var response = $.parseJSON(response);
            console.log(response);
            $("#dnc_result").append(`<tr><td>${response.number}</td><td style="color:${response.color}">${response.result}</td></tr>`);

        });
        return false;
    }
});
</script>