@extends('backend.layouts.app')

@section('title', 'Create Gallery')

@push('styles')

@endpush


@section('content')
    <div class="row clearfix">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-deep-purple">
                    <h2>CREATE ALBUM</h2>
                </div>
                <div class="body">
                    <form action="{{ route('admin.album.store') }}" method="POST">
                        @csrf
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text" name="name" class="form-control" required>
                                <label class="form-label">Album Name</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-indigo btn-lg m-t-15 waves-effect">
                            <i class="material-icons">save</i>
                            <span>SAVE</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush