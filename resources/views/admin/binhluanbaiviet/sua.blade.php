@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">Sửa bình luận bài viết</div>
    <div class="card-body">
        <form action="{{ route('admin.binhluanbaiviet.sua', ['id' => $binhluanbaiviet->id]) }}" method="post">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="baiviet_id">Bài viết</label>
                <select class="form-select @error('baiviet_id') is-invalid @enderror" id="baiviet_id" name="baiviet_id" required>
                    <option value="">-- Chọn --</option>
                    @foreach($baiviet as $value)
                    <option value="{{ $value->id }}" {{ ($binhluanbaiviet->baiviet_id == $value->id) ? 'selected' : '' }}>{{ $value->tieude }}</option>
                    @endforeach
                </select>
                @error('baiviet_id')
                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="noidungbinhluan">Nội dung bình luận</label>
                <textarea name="noidungbinhluan" id="noidungbinhluan" class="form-control" required>{{ $binhluanbaiviet->noidungbinhluan }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-light fa-save"></i> Cập nhật</button>
        </form>
    </div>
</div>
@endsection
@section('javascript')
<script src="{{ asset('public/vendor/ckeditor5/ckeditor.js') }}"></script>
<script>
    ClassicEditor.create(document.querySelector('#noidungbinhluan'), {
        licenseKey: '',
    }).then(editor => {
        window.editor = editor;
    }).catch(error => {
        console.error(error);
    });
</script>
@endsection