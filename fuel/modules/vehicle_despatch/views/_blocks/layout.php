<style>
.weigh-updation label span {
  display: inline-block;
  width: 150px;
}

.weigh-updation input {
  height: auto !important;
  box-sizing: border-box;
  border: 1px solid #999;
}

select.vehiclenumber, select.weighmentWeight {
  padding: 0px 6px !important;
}

.weigh-updation label {
  position: relative;
}

.weigh-updation label em {
  position: absolute;
  right: 5px;
  top: 20px;
}

#contentprocess input {
  width: auto !important;
  margin: 0;
}

#contentprocess td:nth-child(5), #contentprocess td:nth-child(6), #contentprocess td:nth-child(7) {
  text-align: center;
}

.total-bill-weight {
  float: left;
  width: 39%;
  text-align: center;
}

.sum-allocated-weight {
  float: right;
  width: 35%;
  text-align: left;
}

.total {
  display: none;
}

.submit-container {
    clear: both;
    display: none;
}
</style>

<form class="weigh-updation">
  <section>
    <p>
      <label for="card">
        <span>Date:</span>
      </label>
      <input id="date" name="date" type="text"/>
    </p>
    <p>
      <label for="card">
        <span>Vehicle No.:</span>
      </label>
      <select name="vehiclenumber" class="vehiclenumber"></select>
    </p>
    <p>
      <label for="card">
        <span>Weighment Slip Weight:</span>
      </label>
      <select name="weighmentWeight" class="weighmentWeight"></select>
    </p>
  </section>
  <section>
  <div id="contentsholderprocess" class="flexcroll" style="width:100%; height:300px; overflow-x:hidden; overflow-y:auto;">
    <div id="contentprocess" style="width:100%; overflow:hidden;">
      <div id="DynamicGrid_2">
        <!-- No Record! -->
      </div>
    </div>
  </div>
</section>
</br>
</br>
</br>
</br>
</br>
</br>
<div class="submit-container">
  <input class="btn btn-success" id="save-weighment-outward" type="button" value="Save Weighment Details" onclick="saveWeightmentDetails();">
</div>
</form>
<script>
$(function() {
  $( "#date" ).picker({
    onSelect: function(datetext, inst) {
      $.ajax({
          type: "POST",
          url: "<?php echo fuel_url('vehicle_despatch/getOutwardVehiclesWithDate');?>",
          data: "date="+datetext,
          dataType: "json"
          }).done(function( msg ) {
            if(msg.length) {
              var strSelect = "<option value=''>Select Vehicle Number</option>";
              for (var i = 0; i < msg.length; i++) {
                strSelect += "<option value='"+msg[i].vehiclenumber+"'>"+msg[i].vehiclenumber+"</option>";
              }
            }
            $('.vehiclenumber').html(strSelect);
          });
    }
  });

  $('.vehiclenumber').on('change', function (e) {
    $("option:selected", this);
    var vehicleNumber = this.value;
    if(vehicleNumber) {
        $.ajax({
            type: "POST",
            url: "<?php echo fuel_url('vehicle_despatch/fetchWeighmentsWithDateAndVehicleNumber');?>",
            data: "vehicleNumber="+vehicleNumber+"&date="+$('#date').val(),
            dataType: "json"
        }).done(function( msg ) {
            if(msg.length) {
                var strSelect = "<option value=''>Select Weighment slip weight</option>";
                for (var i = 0; i < msg.length; i++) {
                    strSelect += "<option value='"+msg[i].weight+"'>"+msg[i].weight+"</option>";
                }
            }
            $('.weighmentWeight').html(strSelect);
        });
    }
  });

  $('.weighmentWeight').on('change', function (e) {
        $("option:selected", this);
        var selectWeight = this.value;
        if(selectWeight) {
            displayWeighments(selectWeight);
        }
  });
});

function displayWeighments() {
  $('#DynamicGrid_2').hide();
  var date = $('#date').val();
  var vehiclenumber = $('.vehiclenumber').val();
  var weight = $('.weighmentWeight').val();

  var loading = '<div id="DynamicGridLoading_2"><img src="<?=img_path() ?>loading.gif" /><span> Loading Processing Details... </span></div>';
    $("#contentprocess").empty();
    $('#contentprocess').html(loading);
    $.ajax({
        type: "POST",
        url: "<?php echo fuel_url('vehicle_despatch/displayWeightmentDetails');?>",
        data: "date="+date+"&vehiclenumber="+vehiclenumber+"&weight="+weight,
        dataType: "json"
        }).done(function( msg ) {
          if(msg.length == 0) {
            $('#DynamicGrid_2').hide();
            $('#DynamicGridLoading_2').hide();
            var loading1 = '<div id="error_msg">No Result!</div>';
            $('#contentprocess').html(loading1);
          } else{
                var partydata = [];
                for (var i = 0; i < msg.length; i++) {
                  var item = msg[i];
                  // $('#txtRateTotal').val(item.rate);
                  var thisdata = {};
                  thisdata[''] = i+1;
                  thisdata["Coil Number"] = item.vIRnumber;
                  thisdata["Partyname"] = item.partyname;
                  thisdata["Bill Number"] = item.billnumber;
                  thisdata["Date"] = item.dBillDate;
                  thisdata["Item Description"] = item.vDescription;
                  thisdata["Thickness"] = item.fThickness;
                  thisdata["Width"] = item.fWidth;
                  thisdata["Length"] = item.fLength;
                  thisdata["No of Pcs"] = item.ntotalpcs;
                  thisdata["Billed Weight in Kgs"] = item.billWeight;
                  thisdata["Allocated material wt as per weigh bridge"] = item.materialWeight;
                  thisdata["Allocated Packing Wt"] = item.packagingWeight;
                  thisdata["Total Allocated Wt"] = item.totalAllocatedWeight;

                  partydata.push(thisdata);
                }
                if (partydata.length) {
                // If there are files
                  $('#DynamicGrid_2').hide();
                  $('#DynamicGridLoading_2').hide();
                  $('#contentprocess').html(CreateTableViewX(partydata, "lightPro", true));
                  var lcScrollbar = $('#contentsholderprocess');
                  fleXenv.updateScrollBars(lcScrollbar);
                } else {
                  $('#DynamicGrid_2').hide();
                  $('#DynamicGridLoading_2').hide();
                  var loading1 = '<div id="error_msg">No Result!</div>';
                  $('#content').html(loading1);
                  var lfScrollbar = $('#contentsholderprocess');
                  fleXenv.updateScrollBars(lfScrollbar);
                }
          }
    });
}
</script>
