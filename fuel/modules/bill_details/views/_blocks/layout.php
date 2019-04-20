<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
<script src="<?=$this->asset->js_path('datatables.min', 'coil_labels')?>"></script>
<div class="container">
<div id="dialog" title="Label">
    <div id="accordion">
    </div>
</div>
	<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Bill Number</th>
                <th>Coil Number</th>
                <th>Bill Date</th>
                <th>Partyname</th>
                <th>Bill Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
				<th>Bill Number</th>
                <th>Coil Number</th>
                <th>Bill Date</th>
                <th>Partyname</th>
                <th>Bill Status</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
</div>

<script>
function cancel_bill(billNo) {
    var href = '<?php echo site_url("bill_details/cancel_bill")."/?billno="; ?>';
    if (confirm("Are you sure you want to cancel the bill with bill number "+billNo) == true) {
        $.ajax({  
            type: "POST",  
            url : href+billNo,
            success: function(msg) {
                if(msg == 1) {
                    alert('Bill number '+billNo+' has been cancelled.');
                    $('#example').DataTable().ajax.reload();
                }
            }
        });
    } else 
        return false;
}

function delete_bill(billNo) {
    var href = '<?php echo site_url("bill_details/delete_bill")."/?billno="; ?>';
    if (confirm("Are you sure you want to delete the bill with bill number "+billNo) == true) {
        $.ajax({  
            type: "POST",  
            url : href+billNo,
            success: function(msg) {
                if(msg == 1) {
                    alert('Bill number '+billNo+' has been deleted.');
                    $('#example').DataTable().ajax.reload();
                }
            }
        });
    } else 
        return false;
}

	$(document).ready(function() {
      var selectedCoilId = null;
      var selectedCoilCompanyName = null;
        
      var table = $('#example').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": "<?php echo fuel_url('bill_details/list_bill_details');?>",
			"order": [[ 0, "desc" ]],
            "columnDefs": [ 
                {
                    "aTargets": 0,
                    "render": function ( data, type, row ) {
                            return '<a target="_blank" href="search?searchType=bill&searchValue='+data+'">'+data+'</a>';
                    },
                },
                {
                    "aTargets": 1,
                    "render": function ( data, type, row ) {
                            return '<a target="_blank" href="search?searchType=coil&searchValue='+data+'">'+data+'</a>';
                    },
                },
                {
                "aTargets": [5],
                "mData": null,
                "mRender": function (data, type, full) {
                   <?php 
                   	$CI =& get_instance();
                    $userdata = $CI->fuel_auth->user_data(); ?>
                    var returnStr = '<a title="Duplicate Bill" target="_blank" href="bill_details/duplicate_bill/?billno='+data[0]+'"><span class="badge badge-success" style="color: #FFFFFF;background-color: #468847;">Duplicate Bill</span></a>';
                    var superadmin = "<?php echo $userdata['super_admin'];?>";
                    if(superadmin == 'yes') {
                        returnStr += '&nbsp;<a class="cancel_bill" title="Cancel Bill" onClick="cancel_bill('+data[0]+');" href="javascript:void(0);"><span class="badge badge-warning" style="color: #FFFFFF;background-color: #f89406;">Cancel bill</span></a>&nbsp;';
                        if(data['latest']) {
                            returnStr += '<a title="Delete Bill" onClick="delete_bill('+data[0]+');" href="javascript:void(0)"><span class="badge badge-error" style="color: #FFFFFF;">Delete Bill</span></a>';
                        }    
                    }
                    return returnStr;
                }
            } ]
        });
    } );
</script>

