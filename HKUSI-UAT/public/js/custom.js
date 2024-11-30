//==============search-box-menu==============
$('.fliter-roll').click(function() {
    $(".filter-drop-box").addClass("open-filter");
});

$(document).mouseup(function(e) {
    var container = $('.filter-drop-box');
    if (!container.is(e.target) && container.has(e.target).length === 0) {
        container.removeClass("open-filter");
    }
});
/* Check is numeric */
function isNumber(evt) {
    evt = evt ? evt : window.event;
    var charCode = evt.which ? evt.which : evt.keyCode;
    if(charCode == 46){
        return true;
    }
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

// check only letter
$('.name-val').keypress(function (e) {
    var regex = new RegExp("^[a-zA-Z ]+$");
    var strigChar = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(strigChar)) {
        return true;
    }
    return false
});

function randomPassword() {
    var length = 8;
    var chars =
        "abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    var pass = "";
    for (var x = 0; x < length; x++) {
        var i = Math.floor(Math.random() * chars.length);
        pass += chars.charAt(i);
    }
    pass += "P";
    $(".password2").val(pass);
}


function showPass() {
    $(".toggle").toggleClass("fa-eye fa-eye-slash");
    var x = document.getElementById("password");
    if (x.type === "password") {
      x.type == "text";
    }else {
      x.type == "password";
    }
    
}


  function change_user_status_info(ev) {
    ev.preventDefault();
    var urlToRedirect = event.currentTarget.getAttribute('href');
    Swal.fire({
        title: "Are you sure?",
        text: "Are you sure to change status?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Change it.",
    }).then((result) => {
        if (result.isConfirmed) {
            //document.getElementById("delete_form_" + id).submit();
            window.location.href = urlToRedirect;
        }
    });
    return false;
}

function change_member_status_info(ev) {
    ev.preventDefault();
    var urlToRedirect = event.currentTarget.getAttribute('href');
    Swal.fire({
        title: "Are you sure?",
        text: "Are you sure to change status?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Change it.",
    }).then((result) => {
        if (result.isConfirmed) {
            //document.getElementById("delete_form_" + id).submit();
            window.location.href = urlToRedirect;
        }
    });
    return false;
}


function delete_info(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Delete it.",
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("delete_form_" + id).submit();
        }
    });
}

function delete_member(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Delete it.",
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("delete_form_" + id).submit();
        }
    });
}

function updateimage(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Update it.",
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("myfile" + id).submit();
        }
    });
}
if($('#select2Multiple').length>0){
    $('#select2Multiple').select2({
        placeholder: "Select an Country",
    });
}
if($('#select2Multiple1').length>0){
    $('#select2Multiple1').select2({
        placeholder: "Select an Programme",
    });
}
if($('#select2Multiple3').length>0){
    $('#select2Multiple3').select2({
        placeholder: "Select a Member",
    });
}


$('.datepicker').datepicker({
  format: "yyyy-mm-dd",
  autoclose: true  
});

$(document).ready(function() {
    $('.timepicker').timepicker({ 
        timeFormat: 'H:mm',
    });
  });








 




