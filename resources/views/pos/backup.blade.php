@extends('layouts.app')
@section('title','Backup')
@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h6 class="mb-0">Data Security & Backup</h6>
  </div>
  <div class="card-body">
    @if(!$hasPackage)
      <div class="alert alert-warning">Backup package (spatie/laravel-backup) is not installed. Install it to enable backups:
        <code>composer require spatie/laravel-backup</code>
      </div>
    @else
      <div class="alert alert-info">You can trigger a manual backup now. Automated scheduled backups can be configured as a cron later.</div>
    @endif
    <form method="post" action="{{ route('pos.backup.run') }}">
      @csrf
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" value="1" id="with_notifications" name="with_notifications">
        <label class="form-check-label" for="with_notifications">
          Send notifications (requires mail properly configured)
        </label>
      </div>
      <div class="d-flex gap-2">
        <button class="btn btn-primary" {{ $hasPackage ? '' : 'disabled' }}>Run Backup</button>
        <a href="{{ route('pos.backup.sql') }}" class="btn btn-outline-secondary">Download SQL</a>
      </div>
    </form>
  </div>
</div>
@endsection
