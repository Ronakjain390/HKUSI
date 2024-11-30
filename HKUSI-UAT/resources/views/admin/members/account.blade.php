<div class="card custom-card profile-details">
    <div class="basic-details">
        <h6 class="card-heading">Password</h6>
    </div>
    
    <div class="acoount-page form-setting">
        <form action="{{route('admin.members.updateMemberPassword',$MemberInfo->id)}}" method="post" id="quickForm" autocomplete="off">
        @csrf
            <div class="form-flex form-password-toggle">
                <label class="form-label" for="multicol-password">Password</label>
                <div class="col-input">
                    <div class="input-group input-group-merge">
                        <input type="password" id="password" name="password" class="form-control password2" placeholder="Teq7%KxS59v6aY" aria-describedby="password2" required autocomplete="off">
                        <span class="input-group-text cursor-pointer s1"><i toggle="#password-field" class="fa fa-eye-slash toggle1"></i></span>
                        <span class="input-group-text cursor-pointer"><i class="ti ti-refresh" onclick="return randomPassword();"></i></span>
                    </div>
                    <div class="form-check-settings">
                        <label class="form-check mt-2">
                            <input name="send_email" type="checkbox" class="form-check-input checkbox">
                            <span class="form-check-label form-label">Send password reset email</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-btn">
                <button class="btn action-btn" type="submit">Reset Password</button>
                <button class="btn cancel-btn">Cancel</button>
            </div>
        </form>
    </div>
@push('foorterscript')
    <script>
         $(document).ready(function () {
            
            var value = $("#password").val();
           
            $.validator.addMethod("checklower", function (value) {
                return /[a-z]/.test(value);
            });
            $.validator.addMethod("checkupper", function (value) {
                return /[A-Z]/.test(value);
            });
            $.validator.addMethod("checkdigit", function (value) {
                return /[0-9]/.test(value);
            });
            $.validator.addMethod("pwcheck", function (value) {
                return (
                    /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) &&
                    /[a-z]/.test(value) &&
                    /\d/.test(value) &&
                    /[A-Z]/.test(value)
                );
            });
            $("#quickForm").validate({
                rules: {
                    
                    password: {
                        required: true,
                        minlength: 8,
                        checklower: true,
                        checkupper: true,
                        checkdigit: true,
                    },
                },
                messages: {            
                    
                    password: {
                        required: "Please enter password",
                        minlength:
                            "Your password will be min 8 characters, 1 uppercase and 1 Digit",
                        checklower: "Need atleast 1 lowercase alphabet",
                        checkupper: "Need atleast 1 uppercase alphabet",
                        checkdigit: "Need atleast 1 digit",
                    },
                    
                },
                errorElement: "span",
                errorPlacement: function (error, element) {
                    element.closest(".col-input").append(error);
                },
            });

            $('.checkbox').click(function () {
                if($(this).is(":checked")) {
                    $(".toggle1").removeClass("fa-eye");
                    $(".toggle1").addClass("fa-eye-slash");
                    $("#password").attr('type', 'password');
                }
            });
            $('.toggle1').click(function () {
                $(".toggle1").toggleClass("fa-eye fa-eye-slash");
                var x = document.getElementById("password");
                if (x.type === "password") {
                  x.type == "text";
                }else {
                  x.type == "password";
                }
            });

        });
    </script>
@endpush
</div>

