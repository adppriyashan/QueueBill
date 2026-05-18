@extends('layouts.app')

@section('title', 'Activity Audit Logs - QueueBill')
@section('page_title', 'Activity Transaction Logs')

@section('content')
<!-- Header Controls -->
<div class="card mb-4 border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-1">QueueBill Delivery Audits</h5>
        <p class="text-secondary mb-0 fs-7">Review delivery outcomes, resend tracking logs, and simulated Google Drive storage uploads generated via automation schedules or retroactive revisions.</p>
    </div>
</div>

<div class="row g-4">
    <!-- Section 1: Simulated Email Broadcast Log Tracker -->
    <div class="col-12 col-xl-6">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-bottom p-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 p-2 bg-primary-soft text-primary me-3">
                        <i class="far fa-envelope-open fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Outgoing Email Logs</h5>
                        <span class="text-secondary fs-8">Simulated billing and statement dispatch logs</span>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-premium mb-0 align-middle fs-7">
                        <thead>
                            <tr>
                                <th>Billing Target</th>
                                <th>Subject Title</th>
                                <th>Date / Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($emailLogs as $log)
                                <tr>
                                    <td>
                                        <div>
                                            <span class="fw-bold text-dark d-block fs-8">{{ $log->invoice?->company?->name ?? 'Deleted Client' }}</span>
                                            <span class="text-muted fs-9">To: {{ $log->recipient }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="fw-semibold text-primary d-block">{{ $log->subject }}</span>
                                            <small class="text-muted d-block text-truncate fs-9" style="max-width: 200px;" title="{{ $log->body }}">{{ $log->body }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-secondary fs-8">{{ $log->created_at->format('M d, Y H:i') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-soft text-success"><i class="fas fa-check me-1"></i>{{ strtoupper($log->status) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted fs-8">No email broadcasts tracked in audit history.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($emailLogs->hasPages())
                    <div class="px-4 py-3 border-top bg-light">
                        {{ $emailLogs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Section 2: Simulated Google Drive Upload Log Tracker -->
    <div class="col-12 col-xl-6">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-bottom p-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 p-2 bg-success-soft text-success me-3">
                        <i class="fab fa-google-drive fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Google Drive Upload Logs</h5>
                        <span class="text-secondary fs-8">Simulated directory syncing and storage path logs</span>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-premium mb-0 align-middle fs-7">
                        <thead>
                            <tr>
                                <th>File Uploaded</th>
                                <th>Google Drive path</th>
                                <th>Outcome</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($driveLogs as $log)
                                <tr>
                                    <td>
                                        <div>
                                            <strong class="text-dark d-block fs-8">{{ $log->file_name }}</strong>
                                            <span class="text-muted d-block fs-9">{{ $log->created_at->format('M d, Y H:i') }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <code class="text-success fs-8"><i class="fas fa-folder me-1"></i>{{ Str::limit($log->google_drive_path, 22) }}</code>
                                    </td>
                                    <td>
                                        <span class="text-secondary fs-8" title="{{ $log->details }}">{{ Str::limit($log->details, 22) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-soft text-success"><i class="fas fa-check me-1"></i>{{ strtoupper($log->status) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted fs-8">No Google Drive sync actions logged.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($driveLogs->hasPages())
                    <div class="px-4 py-3 border-top bg-light">
                        {{ $driveLogs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
