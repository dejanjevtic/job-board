@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
          <div style="margin:15px;">{!! !empty($message) ? $message : '' !!}</div><a href="/post">Add New Job</a>
            <div class="card">

                <div class="card-header">Job List</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                   
            </div>
        </div>

        
    </div>
</div>
@endsection
