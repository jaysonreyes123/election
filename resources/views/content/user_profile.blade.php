@extends('layout._main',["title"=>"Profile"])
@section('content')
<section class="section profile">
  <div class="pagetitle">
    <h1>Profile</h1>
  </div>
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-body pt-3">
            <!-- Bordered Tabs -->
            <ul class="nav nav-tabs nav-tabs-bordered">

              <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
              </li>

              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
              </li>

              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
              </li>

            </ul>
            <div class="tab-content pt-2">

              <div class="tab-pane fade show active profile-overview" id="profile-overview">
                <h5 class="card-title">Profile Details</h5>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label ">First Name</div>
                  <div class="col-lg-9 col-md-8">{{$user_model->firstname}}</div>
                </div>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label ">Last Name</div>
                  <div class="col-lg-9 col-md-8">{{$user_model->lastname}}</div>
                </div>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label ">User Name</div>
                  <div class="col-lg-9 col-md-8">{{$user_model->username}}</div>
                </div>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label ">Email</div>
                  <div class="col-lg-9 col-md-8">{{$user_model->email}}</div>
                </div>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label ">Role</div>
                  <div class="col-lg-9 col-md-8">{{$user_model->roles_->name}}</div>
                </div>

              </div>

              <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                <!-- Profile Edit Form -->
                <form id="edit-profile-form" method="post">
                  @csrf
                  <div class="row mb-3">
                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">First Name</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="firstname" id="firstname" type="text" class="form-control"  value="{{$user_model->firstname}}">
                      <div class="form-text text-danger" id="firstname_validation"></div>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Last Name</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="lastname" id="lastname" type="text" class="form-control"  value="{{$user_model->lastname}}">
                      <div class="form-text text-danger" id="lastname_validation"></div>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">User Name</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="username" id="username" type="text" class="form-control"  value="{{$user_model->username}}">
                      <div class="form-text text-danger" id="username_validation"></div>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="email" id="email" type="text" class="form-control"  value="{{$user_model->email}}">
                      <div class="form-text text-danger" id="email_validation"></div>
                    </div>
                  </div>
                  

                  <div class="text-center">
                    <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                  </div>
                </form><!-- End Profile Edit Form -->

              </div>

              <div class="tab-pane fade pt-3" id="profile-change-password">
                <!-- Change Password Form -->
                <form id="change-password-form">

                  <div class="row mb-3">
                    <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="password" type="password" class="form-control" id="password">
                      <div class="form-text text-danger" id="password_validation"></div>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="new_password" type="password" class="form-control" id="new_password">
                      <div class="form-text text-danger" id="new_password_validation"></div>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="confirmation_password" type="password" class="form-control" id="confirmation_password">
                      <div class="form-text text-danger" id="confirmation_password_validation"></div>
                    </div>
                  </div>

                  <div class="text-center">
                    <button type="submit" class="btn btn-primary">Change Password</button>
                  </div>
                </form><!-- End Change Password Form -->

              </div>

            </div><!-- End Bordered Tabs -->

          </div>
        </div>

      </div>
    </div>
  </section>
@push('script')
  <script>
    $("#edit-profile-form").on('submit',function(e){
      e.preventDefault();
      $(".form-text").text("");
      $("#loader").show();
        $.ajax({
          url:"/users/profile/save",
          method:"post",
          data:$(this).serialize(),
          success:function(data){
              swal.fire({
                icon:"success",
                title:"profile details successfully changed"
              })
              $("#loader").hide();
              location.reload();
          },
          error:function(err){
              const key = Object.keys(err.responseJSON.errors);
              key.map((item,index)=>{
                  $("#"+item+"_validation").text(err.responseJSON.errors[item][0])
              })
              $("#loader").hide();
            }
        })
    })

    $("#change-password-form").on('submit',function(e){
      e.preventDefault();
      $(".form-text").text("");
      $("#loader").show();
      $.ajax({
          url:"/users/profile/changepassword",
          method:"post",
          data:$(this).serialize(),
          success:function(data){
              swal.fire({
                icon:"success",
                title:"password successfully changed"
              })
              $("#loader").hide();
              location.reload();
          },
          error:function(err){
              const key = Object.keys(err.responseJSON.errors);
              key.map((item,index)=>{
                  $("#"+item+"_validation").text(err.responseJSON.errors[item][0])
              })
              $("#loader").hide();
            }
        })
    })
  </script>
@endpush
@endsection
