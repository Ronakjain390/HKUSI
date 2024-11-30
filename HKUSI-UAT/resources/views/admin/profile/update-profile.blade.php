@extends('admin.layouts.index')
@section('content')    
<div class="container-xxl flex-grow-1 container-p-y">  
    @if ($message = Session::get('success'))
      <div style="color:#6FC5DF;">
        <p>{{ $message }}</p>
      </div>
    @endif
    @if ($message = Session::get('errormessage'))
      <div class="alert text-red-500"> <strong>Whoops!</strong> There were some problems with your input.<br>
        <br>
        <ul>
          <li>{{ $message }}</li>
        </ul>
      </div>
    @endif
    @if (count($errors) > 0)
      <div class="alert text-red-500"> <strong>Whoops!</strong> There were some problems with your input.<br>
        <br>
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif     
    {!! Form::open(array('route' => 'admin.updateProfile','method'=>'POST','files' => true,'class'=>'edit-form')) !!}
    <div class="card custom-card profile-details">
        <!-- <div class="basic-details">
            <h6 class="card-heading"> Info</h6>
        </div> -->
        <div class="table-responsive table-details">
            <table class="table">
                <tbody>
                     <tr>
                        <th class="t-basic">Name</th>
                        <td><input type="text" name="name" value="{{Auth::user()->name}}" class="form-control" placeholder="Name"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Email Address</th>
                        <td><input type="email" value="{{Auth::user()->email}}" readonly name="email" class="form-control" placeholder="Email Address"></td>
                    </tr>  
                </tbody>
            </table>
        </div>
    </div>
    <div class="card custom-card">
        <div class="form-btn">
            <button type="submit" class="btn action-btn">Save Changes</button>
            <button type="reset" class="btn cancel-btn">Delete</button>
        </div>
    </div>
{!! Form::close() !!}
<!-- / Content -->
<div class="content-backdrop fade "></div>
<!-- Content wrapper -->
</div>
<!-- / Layout page -->
@endsection