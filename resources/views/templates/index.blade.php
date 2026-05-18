@extends('layouts.app')

@section('title', 'Invoice Structure Templates - QueueBill')
@section('page_title', 'Dynamic Invoice Structure Templates')

@section('content')
<!-- Header Controls -->
<div class="card mb-4 border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h5 class="fw-bold mb-1">Structure Templates</h5>
                <p class="text-secondary mb-0 fs-7">Configure separate branding rules, layouts, and sender overrides for different lines of business.</p>
            </div>
            <a href="{{ route('templates.create') }}" class="btn btn-primary d-inline-flex align-items-center">
                <i class="fas fa-plus me-2"></i>Create New Template
            </a>
        </div>
    </div>
</div>

<!-- Tabular Grid -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-premium mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="width: 30%">Template Title</th>
                        <th style="width: 25%">slug URL Parameter</th>
                        <th style="width: 20%">Sender Email Override</th>
                        <th style="width: 15%">Status</th>
                        <th style="width: 10%" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($templates as $template)
                        <tr>
                            <td>
                                <div>
                                    <span class="fw-bold text-dark d-block fs-6">{{ $template->title }}</span>
                                    <span class="text-muted fs-8">Added {{ $template->created_at->format('M d, Y') }}</span>
                                </div>
                            </td>
                            <td>
                                <code class="fs-7 bg-light text-primary px-2 py-1 rounded">/invoices/templates/{{ $template->slug }}</code>
                            </td>
                            <td>
                                @if($template->sender_email)
                                    <span class="fw-medium text-dark"><i class="far fa-envelope text-muted me-1"></i>{{ $template->sender_email }}</span>
                                @else
                                    <span class="text-muted fs-8 font-italic"><i class="fas fa-cog text-muted me-1"></i>System Default Fallback</span>
                                @endif
                            </td>
                            <td>
                                @if($template->status === 'active')
                                    <span class="badge-premium bg-success-soft text-success"><i class="fas fa-circle fs-8 me-1"></i>Active</span>
                                @else
                                    <span class="badge-premium bg-danger-soft text-danger"><i class="fas fa-circle fs-8 me-1"></i>Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                        <li>
                                            <a class="dropdown-item py-2 text-primary" href="{{ route('templates.preview', $template->slug) }}" target="_blank">
                                                <i class="fas fa-external-link-alt fa-fw me-2"></i>Live Dynamic Demo
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-2" href="{{ route('templates.edit', $template) }}">
                                                <i class="fas fa-edit fa-fw me-2 text-warning"></i>Edit Configuration
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('templates.destroy', $template) }}" method="POST" onsubmit="return confirm('Are you sure you want to deactivate/delete this template?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item py-2 text-danger">
                                                    <i class="fas fa-trash-alt fa-fw me-2"></i>Deactivate / Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted mb-3">
                                    <i class="fas fa-file-invoice fa-3x mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-0 fw-semibold">No invoice structure templates registered.</p>
                                    <p class="fs-7">Create templates to override sender profiles and custom layout branding rules.</p>
                                </div>
                                <a href="{{ route('templates.create') }}" class="btn btn-primary btn-sm">
                                    Create First Template
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($templates->hasPages())
            <div class="px-4 py-3 border-top bg-light">
                {{ $templates->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
