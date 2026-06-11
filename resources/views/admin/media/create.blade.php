@extends('admin.layout')

@section('title', 'Upload PDF')

@section('content')
    <h1>Upload PDF</h1>
    <p class="admin-help">
        Upload PDF files such as Annual Reports. After upload, copy the file link and insert it into a page.
    </p>

    <form method="post" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="admin-form">
        @csrf

        <div class="admin-card">
            <div class="form-group">
                <label for="title">File Title</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Annual Report 2025" required>
                @error('title')
                    <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="file">PDF File</label>
                <input type="file" id="file" name="file" accept="application/pdf" required>
                <small>PDF only. Maximum file size: 10MB.</small>
                @error('file')
                    <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="admin-form-actions">
                <button type="submit" class="btn btn-primary">Upload File</button>
                <a href="{{ route('admin.media.index') }}" class="btn">Cancel</a>
            </div>
        </div>
    </form>
@endsection
