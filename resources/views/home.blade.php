@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
          <div style="margin:15px;">{!! !empty($message) ? $message : '' !!}</div><a href="/post">Add New Job</a>
            <div class="card">

                <div class="card-header">Your Job List</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                   
            </div>
            @if(!empty($jobs))
                @foreach ($jobs as $job)
                   
                                <div  style="width:100%; padding:15px; color:#3490dc;">
                                    <div><h2>{!! $job->title !!}</h2>
                                        {!!str_limit($job->description, $limit = 100, $end = '...')!!}
                                      
                                    </div>
                                </div>
                                <hr>
                    
                @endforeach
            @endif
        </div>

        
    </div>
</div>
@endsection
