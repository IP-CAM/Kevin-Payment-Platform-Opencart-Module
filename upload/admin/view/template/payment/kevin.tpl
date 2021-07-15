<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-kevin" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
       <div class="panel panel-default">
     <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form  action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-kevin" class="form-horizontal">
        <ul id="tabs" class="nav nav-tabs">
          <li class="active"><a href="#general" data-toggle="tab"><?php echo $entry_general; ?></a></li>
          <li><a href="#order_statuses" data-toggle="tab"><?php echo $entry_order_statuses; ?></a></li>
		  <li><a href="#instructions" data-toggle="tab"><?php echo $entry_instructions; ?></a></li>
        </ul>
        
        <div class="tab-content">
        <div class="tab-pane active" id="general">

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-clientid"><?php echo $entry_client_id; ?></label>
            <div class="col-sm-10">
              <input type="text" name="kevin_client_id" value="<?php echo $kevin_client_id; ?>" placeholder="<?php echo $entry_client_id; ?>" id="input-clientid" class="form-control" />
              <?php if ($error_client_id) { ?> 
              <div class="text-danger"><?php echo $error_client_id; ?></div>
              <?php } ?> 
            </div>
          </div>
			
		<div class="form-group required">
            <label class="col-sm-2 control-label" for="input-secret"><?php echo $entry_client_secret; ?></label>
            <div class="col-sm-10">
              <input type="text" name="kevin_client_secret" value="<?php echo $kevin_client_secret; ?>" placeholder="<?php echo $entry_client_secret; ?>" id="input-secret" class="form-control" />
              <?php if ($error_client_secret) { ?> 
              <div class="text-danger"><?php echo $error_client_secret; ?></div>
              <?php } ?> 
            </div>
          </div>
			
			<div class="form-group required">
            <label class="col-sm-2 control-label" for="input-company"><?php echo $entry_client_company; ?></label>
            <div class="col-sm-10">
              <input type="text" name="kevin_client_company" value="<?php echo $kevin_client_company; ?>" placeholder="<?php echo $entry_client_company; ?>" id="input-company" class="form-control" />
              <?php if ($error_client_company) { ?> 
              <div class="text-danger"><?php echo $error_client_company; ?></div>
              <?php } ?> 
            </div>
          </div>
			
			<div class="form-group required">
            <label class="col-sm-2 control-label" for="input-iban"><span data-toggle="tooltip" title="<?php echo $help_iban_format; ?>"><?php echo $entry_client_iban; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="kevin_client_iban" value="<?php echo $kevin_client_iban; ?>" placeholder="<?php echo $entry_client_iban; ?>" id="input-iban" class="form-control" />
              <?php if ($error_client_iban_valid) { ?> 
              <div class="text-danger"><?php echo $error_client_iban_valid; ?></div>
              <?php } elseif ($error_client_iban_empty) { ?>
				 <div class="text-danger"><?php echo $error_client_iban_empty; ?></div>
				<?php } ?> 
            </div>
          </div>
			
			<div class="form-group">
            <label class="col-sm-2 control-label" for="input-redirect"><?php echo $entry_redirect_preferred; ?></label>
            <div class="col-sm-10">
              <select name="kevin_redirect_preferred" id="input-redirect" class="form-control">
                <?php if ($kevin_redirect_preferred) { ?> 
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?> 
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?> 
              </select>
            </div>
          </div>
			
			<?php foreach ($languages as $language) { ?>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-kevin-title<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_bank_title; ?>"><?php echo $entry_kevin_title; ?></span></label>
            <div class="col-sm-10">
              <div class="input-group"><span class="input-group-addon"><img src="<?php echo $language['flag_image']; ?>" title="<?php echo $language['name']; ?>" /></span>
				  <input type="text" name="kevin_title<?php echo $language['language_id']; ?>" id="input-kevin-title<?php echo $language['language_id']; ?>" value="<?php if (${'kevin_title' . $language['language_id']}) { ?><?php echo ${'kevin_title' . $language['language_id']}; ?><?php } ?>" placeholder="<?php echo $entry_kevin_title; ?>" class="form-control"/>

              </div>
             <?php if (${'error_title' . $language['language_id']}) { ?>
              <div class="text-danger"><?php echo ${'error_title' . $language['language_id']}; ?></div>
              <?php } ?>
            </div>
          </div>
          <?php } ?>

			<div class="form-group form-row">
				
            <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
            <div class="col-sm-2">
			      <tbody>
                  <tr>
                    <td class="text-left"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>"/></a>
                      <input type="hidden" name="kevin_image" value="<?php echo $kevin_image; ?>" id="input-image"/></td>
                  </tr>
                  </tbody>
			</div>

            <label class="col-sm-2 control-label" for="input-height"><span data-toggle="tooltip" title="<?php echo $help_height; ?>"><?php echo $entry_image_height; ?></span></label>
            <div class="col-sm-2">
              <input type="text" name="kevin_image_height" value="<?php echo $kevin_image_height; ?>" placeholder="<?php echo $entry_image_height; ?>" id="input-height" class="form-control" />
            </div>
				
				<label class="col-sm-2 control-label" for="input-width"><span data-toggle="tooltip" title="<?php echo $help_width; ?>"><?php echo $entry_image_width; ?></span></label>
            <div class="col-sm-2">
              <input type="text" name="kevin_image_width" value="<?php echo $kevin_image_width; ?>" placeholder="<?php echo $entry_image_width; ?>" id="input-width" class="form-control" />
            </div>
				
          </div>
			
			<div class="form-group">
            <label class="col-sm-2 control-label" for="input-position"><span data-toggle="tooltip" title="<?php echo $help_position; ?>"><?php echo $entry_position; ?></span></label>
            <div class="col-sm-10">
              <select name="kevin_position" id="input-position" class="form-control">
                <?php if ($kevin_position == 'right') { ?>
                <option value="right" selected="selected"><?php echo $text_right; ?></option>
                <option value="left"><?php echo $text_left; ?></option>
                <?php } else { ?>
                <option value="right"><?php echo $text_right; ?></option>
                <option value="left" selected="selected"><?php echo $text_left; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
			
			<div class="form-group">
            <label class="col-sm-2 control-label" for="input-name-enabled"><span data-toggle="tooltip" title="<?php echo $help_bank_name_enbl; ?>"><?php echo $entry_bank_name_enabled; ?></span></label>
            <div class="col-sm-10">
              <select name="kevin_bank_name_enabled" id="input-name-enabled" class="form-control">
                <?php if ($kevin_bank_name_enabled) { ?> 
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?> 
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?> 
              </select>
            </div>
          </div>
			
			<div class="form-group">
            <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="kevin_total" value="<?php echo $kevin_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
              <span class="help-block"><?php echo $help_total; ?></span>
            </div>
          </div>
			
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <select name="kevin_geo_zone_id" id="input-geo-zone" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?> 
                <?php if ($geo_zone['geo_zone_id'] == $kevin_geo_zone_id) { ?> 
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?> 
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?> 
                <?php } ?> 
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="kevin_status" id="input-status" class="form-control">
                <?php if ($kevin_status) { ?> 
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?> 
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?> 
              </select>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-log"><span data-toggle="tooltip" title="<?php echo $help_log; ?>"><?php echo $entry_log; ?></label>
            <div class="col-sm-10">
              <select name="kevin_log" id="input-log" class="form-control">
                <?php if ($kevin_log) { ?> 
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?> 
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?> 
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="kevin_sort_order" value="<?php echo $kevin_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div> 
			</div>
	
		<div class="tab-pane" id="order_statuses">	
			<div class="form-group">
			  <label class="col-sm-2 control-label" for="order-status-started"><?php echo $entry_started_status; ?></label>
			  <div class="col-sm-10">
				<select name="kevin_started_status_id" class="form-control" id="order-status-started">
				  <?php foreach ($order_statuses as $order_status) { ?> 
					  <?php if ($order_status['order_status_id'] == $kevin_started_status_id) { ?> 
						  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					  <?php } else { ?> 
						  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					  <?php } ?> 
				  <?php } ?> 
				</select>
			  </div>
			</div>
   			<div class="form-group">
			  <label class="col-sm-2 control-label" for="order-completed-status"><?php echo $entry_completed_status; ?></label>
			  <div class="col-sm-10">
				<select name="kevin_completed_status_id" class="form-control"  id="order-completed-status">
				  <?php foreach ($order_statuses as $order_status) { ?> 
					  <?php if ($order_status['order_status_id'] == $kevin_completed_status_id) { ?> 
						  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					  <?php } else { ?> 
						  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					  <?php } ?> 
				  <?php } ?> 
				</select>
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-sm-2 control-label" for="order-pending-status"><?php echo $entry_pending_status; ?></label>
			  <div class="col-sm-10">
				<select name="kevin_pending_status_id" class="form-control" id="order-pending-status">
				  <?php foreach ($order_statuses as $order_status) { ?> 
					  <?php if ($order_status['order_status_id'] == $kevin_pending_status_id) { ?> 
						  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					  <?php } else { ?> 
						  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					  <?php } ?> 
				  <?php } ?> 
				</select>
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-sm-2 control-label" for="order-status-failed"><?php echo $entry_failed_status; ?></label>
			  <div class="col-sm-10">
				<select name="kevin_failed_status_id" class="form-control" id="order-status-failed">
				  <?php foreach ($order_statuses as $order_status) { ?> 
					  <?php if ($order_status['order_status_id'] == $kevin_failed_status_id) { ?> 
						  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					  <?php } else { ?> 
						  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					  <?php } ?> 
				  <?php } ?> 
				</select>
			  </div>
			</div>
			
		</div>	
			
			<div class="tab-pane" id="instructions">
			<?php foreach ($languages as $language) { ?>
          <div class="form-group form-row">
			<div class="row">
            <label class="col-sm-2 control-label" for="input-kevin-ititle<?php echo $language['language_id']; ?>"><?php echo $entry_instruction_title; ?></label>
            <div class="col-sm-10">
              <div class="input-group"><span class="input-group-addon"><img src="<?php echo $language['flag_image']; ?>" title="<?php echo $language['name']; ?>" /></span>
				  <input type="text" name="kevin_ititle<?php echo $language['language_id']; ?>" id="input-kevin-ititle<?php echo $language['language_id']; ?>" value="<?php if (${'kevin_ititle' . $language['language_id']}) { ?><?php echo ${'kevin_ititle' . $language['language_id']}; ?><?php } ?>" placeholder="<?php echo $entry_instruction_title; ?>" class="form-control"/>

              </div>
             </div>
            </div>
         
          <div class="row">
            <label class="col-sm-2 control-label" for="input-kevin-instruction<?php echo $language['language_id']; ?>"><?php echo $entry_kevin_instruction; ?></label>
            <div class="col-sm-10">
              <div class="input-group"><span class="input-group-addon"><img src="<?php echo $language['flag_image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                <textarea name="kevin_instruction<?php echo $language['language_id']; ?>" cols="80" rows="10" placeholder="<?php echo $entry_kevin_instruction; ?>" id="input-kevin-instruction<?php echo $language['language_id']; ?>" class="form-control"><?php if (${'kevin_instruction' . $language['language_id']}) { ?><?php echo ${'kevin_instruction' . $language['language_id']}; ?><?php } ?></textarea>
              </div>
             
            </div>
          </div>
		</div>
          <?php } ?>

         </div>
        </div>
          
        </form>
     
    </div>
  </div>
</div>
</div>
<script type="text/javascript"><!--
	
	$("input#input-iban").on({
  keyup: function(e) {
    if (e.which === 32 || e.which === 189)
      return false;
  },
  change: function() {
    this.value = this.value.replace(/\s/g, "");
  }
});
//--></script>	
<style>
.row {
	margin-left: 0px; 
   	margin-right: 0px; 
}
</style>

<?php echo $footer; ?>