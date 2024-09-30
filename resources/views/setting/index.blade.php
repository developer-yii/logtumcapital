@php
  $labelMain = 'Configuraciones';
  $label = 'Configuraciones';
@endphp
@extends('layouts.main')
@section('title', $labelMain)
@push('css')
    <link href="{{asset('/')}}backend/css/vendor/dataTables.bootstrap5.css" rel="stylesheet" type="text/css" />
@endpush
@section('content')
<div class="row mt-3 mb-3">
    <div class="col-md-6">
        <h4 class="page-title">{{ $labelMain }}</h4>
    </div>
    <div class="col-md-6">
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table id="settings_table" class="table site_table w-100 nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Key</th>
                                    <th>Value</th>
                                    <th>{{ __("translation.Actions") }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div
</div>

{{-- update settings modal --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('settings.store') }}" method="POST" id="add-form">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span>{{ __("translation.Add") }}</span> {{$label}}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-1">
                    <input type="hidden" name="setting_id" id="setting_id">
                    <div class="form-group mb-3">
                        <label for="key" class="form-label">Key</label>
                        <input type="text" id="key" name="key" class="form-control" value="" autocomplete>
                        <span id="error_key" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="value" class="form-label">Value</label>
                        <input type="text" id="value" name="value" class="form-control" value="" autocomplete>
                        <span id="error_value" class="error text-danger"></span>
                    </div>
                </div>
                <div class="d-block modal-footer">
                    <button type="button" class="btn btn-secondary float-start" id="model-cancle-btn" data-bs-dismiss="modal" aria-label="Close">{{ __("translation.Cancel") }}</button>
                    <button type="submit" class="btn btn-success float-end" id="addorUpdateBtn">{{ __("translation.Save") }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('js')
    <script>
        var getSettingsUrl = "{{ route('settings.get') }}";
        var getSettingDetailsUrl = "{{ route('settings.edit', ['id' => '__ID__']) }}";
    </script>
    <script src="{{asset('/')}}backend/js/vendor/jquery.dataTables.min.js"></script>
    <script src="{{asset('/')}}backend/js/vendor/dataTables.bootstrap5.js"></script>
    <script src="{{asset('/')}}backend/page-js/settings.js"></script>
@endpush
