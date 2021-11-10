function getSeatchTradeSymbol(str){
    if(str.length<2){

    }else{
        $.get('/trade/searchTradeSymbol/'+str, {}, function(result){

        });
    }
}

function getDeleteCustomer(id){
    var x = confirm("Are you sure you want to delete?");
    if (x){
          $.get('/customer/getDeleteCustomer/'+id,{}, function(result){
            //   alert(result);
             location.reload();
          });
        return true;
      }
    else
      return false;
}
function getDeleteTradingAccount(id){
    var x = confirm("Are you sure you want to delete?");
    if (x){
          $.get('/trading/getDeleteTradingAccount/'+id,{}, function(result){
            //   alert(result);
             location.reload();
          });
        return true;
      }
    else
      return false;
}
function getDeleteGroup(id){
     var x = confirm("Are you sure you want to delete?");
    if (x){
          $.get('/group/getDeleteGroup/'+id,{}, function(result){
              alert("Data deleted successfull");
             location.reload();
          });
        return true;
      }
    else
      return false;
}


function resetPositions() {
	console.log("Resetting positions");
	clearAllFilters($('#datatable1').DataTable());
	onPositionCategoryChange();
	$("#posStateSelect").val('ALL');
	deselectAllPositions();
}

function showModifyOrders(){
    var chkOrderId = $('#chkAccountId[]').val();
    $('#exampleModal').modal('show');
    $.get('/trade/showModifyOrders', {}, function(result){
        $('#model_content').html("");
        $('#model_content').append(result);
    });
}

function closeModel(){
  $('#MySecondmodal').modal('hide')
}

function enterKeyForTOTP(loginId){
  // alert(loginId);
  $('#MySecondmodal').modal('show')
  $.get('/totp/showfromfortotp', {loginId:loginId}, function(result){
        $('#model_content').html("");
        $('#model_content').append(result);
    });
}

function generateTOTP(loginId){
  var key = $('#kitetotp').val();
  $.get('/totp/store', {loginId:loginId, key:key}, function(res){
    if(res==true){
      $.get('http://localhost/Trade%20Website/authenticator/index.php', {key:key}, function(result){
        if(result=="FAILED"){

        }else{
          $('#totpData').html("");
          $('#totpData').html(result);
        }
      });
    }
  });


}


togglePassword.addEventListener('click', function (e) {
  const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
  password.setAttribute('type', type);
});

toggle2FA.addEventListener('click', function (e) {
  if($('#security_ans').attr("type") == "text"){
    $('#security_ans').attr('type', 'password');
  }else if($('#security_ans').attr("type") == "password"){
    $('#security_ans').attr('type', 'text');
  }
});

function getInactiveUser(id){
  var status = "active";
  $.get('/user/activation', {id:id, status:status}, function(result){
    if(result==1){
      location.reload();
    }else{
      alert("Invalid User");
    }
  });
}

function getActiveUser(id){
  var status = "inactive";
  $.get('/user/activation', {id:id, status:status}, function(result){
    if(result==1){
      location.reload();
    }else{
      alert("Invalid User");
    }
  });
}


function setPriceBaseSchedular(){
  var price_type = $('#price_type').val();
  var schedular_price = $('#schedular_price').val();
  var below_above = $('#below_above').val();
  var set_price = $('#set_price').val();
  var no_of_order = $('#no_of_order').val();
  var validity_type = $('#validity_type').val();
  var schedular_validity = $('#schedular_validity').val();
  var data = JSON.stringify({
    "price_type":price_type,
    "schedular_price":schedular_price,
    "below_above":below_above,
    "set_price":set_price,
    "no_of_order":no_of_order,
    "validity_type":validity_type,
    "schedular_validity":schedular_validity
  });
  $('#exampleModal').modal('hide');
  Cookies.set('schedular', data);
  $('#isScheduleData').css('display', 'block');
  $('#isScheduleData').html('');
}

function setTimeBaseSchedular(){
  var frequency_diff = $('#frequency_diff').val();
  var no_of_order = $('#no_of_order').val();
  var validity_type = $('#validity_type').val();
  var schedular_validity = $('#schedular_validity').val();
  var data = JSON.stringify({
    "frequency_diff":frequency_diff,
    "no_of_order":no_of_order,
    "validity_type":validity_type,
    "schedular_validity":schedular_validity
  });
  $('#exampleModal').modal('hide');
  Cookies.set('schedular', data);
  $('#isScheduleData').css('display', 'block');
  $('#isScheduleData').html('Durgesh');
}

function setPercentageBaseSchedular(){
  var below_above = $('#below_above').val();
  var set_price = $('#set_price').val();
  var no_of_order = $('#no_of_order').val();
  var validity_type = $('#validity_type').val();
  var schedular_validity = $('#schedular_validity').val();
  var percentage= $('#percentage').val();
  var data = JSON.stringify({
    "below_above":below_above,
    "set_price":set_price,
    "percentage":percentage,
    "no_of_order":no_of_order,
    "validity_type":validity_type,
    "schedular_validity":schedular_validity,
  });
  $('#exampleModal').modal('hide');
  Cookies.set('schedular', data);
  $('#isScheduleData').css('display', 'block');
  $('#isScheduleData').html('Durgesh');
}




