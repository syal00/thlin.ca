@extends('admin.layout')

@section('title', 'Upload PDF')
@section('page_title', 'Upload PDF')
@section('page_subtitle', 'Add Annual Reports or other PDF documents for use on website pages.')

@section('content')
    <div class="admin-page-actions">
        <a href="{{ route('admin.media.index') }}" class="btn btn-light">Back to files</a>
    </div>

    <form method="post" action="{{ route('admin.media.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="admin-card">
            <div class="form-group">
                <label class="form-label" for="title">File title</label>
                <input class="form-control" type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Annual Report 2025" required>
                @error('title')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="file">PDF file</label>
                <input class="form-control" type="file" id="file" name="file" accept="application/pdf" required>
                <p class="form-helper">PDF only. Maximum file size: 10MB.</p>
                @error('file')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Upload File</button>
                <a href="{{ route('admin.media.index') }}" class="btn btn-light">Cancel</a>
            </div>
        </div>
    </form>
@endsection
