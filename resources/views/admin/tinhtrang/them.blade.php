@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">Thêm tình trạng</div>
    <div class="card-body">
        <form action="{{route('admin.tinhtrang.them')}}" method="post">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="tinhtrang"><b>Tình trạng</b></label>
                <input type="text" class="form-control  @error('tinhtrang') is-invalid @enderror " id="tinhtrang"
                    name="tinhtrang" value="{{old('tinhtrang')}}" required />
                @error('tinhtrang')
                <div class="invalid-feedback"><strong>{{$message}}</strong></div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa-light fa-save"></i>Thêm vào CSDL</button>
        </form>
    </div>
</div>
@endsection