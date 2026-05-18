@extends('layouts.app')

@section('title', 'Recurring Services Scheduling - QueueBill')
@section('page_title', 'Recurring Billing Schedules')

@section('content')
    <!-- Header Controls -->
    <div class="card mb-4 border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Billing Subscriptions Directory</h5>
                    <p class="text-secondary mb-0 fs-7">Connect buyers into pricing schedules, intervals, and Google Drive
                        upload paths.</p>
                </div>
                <a href="{{ route('services.create') }}" class="btn btn-sm btn-primary d-inline-flex align-items-center">
                    <i class="fas fa-plus me-2"></i>Schedule New Service
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
                            <th>Subscriber Company</th>
                            <th>Service Schedule</th>
                            <th>Timeline Interval</th>
                            <th>Base Price</th>
                            <th>Next Billing Date</th>
                            <th>Drive Path</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                            <tr>
                                <td>
                                    <div>
                                        <a href="{{ route('companies.show', $service->company) }}"
                                            class="fw-bold text-dark text-decoration-none hover-primary">{{ $service->company->name }}</a>
                                        <span class="text-muted d-block fs-8">{{ $service->company->email }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <a href="{{ route('services.show', $service) }}"
                                            class="fw-semibold text-primary text-decoration-none hover-dark d-block">{{ $service->name }}</a>
                                        <span class="text-secondary fs-8">Template:
                                            {{ $service->invoiceStructureTemplate->title }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="fs-8">
                                        <span class="d-block"><strong>From:</strong>
                                            {{ $service->from_date->format('M d, Y') }}</span>
                                        <span class="d-block"><strong>To:</strong>
                                            {{ $service->to_date->format('M d, Y') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark">@currency($service->base_cost)</span>
                                        <span class="badge bg-primary-soft text-primary text-capitalize fs-8 mt-1"
                                            style="max-width: fit-content;">Every {{ $service->recurring_cadence }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="far fa-calendar-alt text-muted me-2"></i>
                                        <span
                                            class="text-dark fw-semibold">{{ $service->next_billing_date->format('M d, Y') }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($service->google_drive_path)
                                        <code class="fs-8 bg-light text-success px-2 py-1 rounded"
                                            title="{{ $service->google_drive_path }}"><i class="fab fa-google-drive me-1"></i>{{ Str::limit($service->google_drive_path, 20) }}</code>
                                    @else
                                        <span class="text-muted fs-8 font-italic"><i class="fas fa-minus me-1"></i>Not Set</span>
                                    @endif
                                </td>
                                <td>
                                    @if($service->status === 'active')
                                        <span class="badge-premium bg-success-soft text-success"><i
                                                class="fas fa-circle fs-8 me-1"></i>Active</span>
                                    @else
                                        <span class="badge-premium bg-danger-soft text-danger"><i
                                                class="fas fa-circle fs-8 me-1"></i>Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('services.show', $service) }}"
                                            class="btn btn-sm bg-primary-soft text-primary" title="Config Adjustments">
                                            <i class="fas fa-cog"></i>
                                        </a>
                                        <a href="{{ route('services.edit', $service) }}"
                                            class="btn btn-sm bg-warning-soft text-warning" title="Edit Schedule">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('services.destroy', $service) }}" method="POST"
                                            class="d-inline-block"
                                            onsubmit="return confirm('Are you sure you want to remove this scheduled subscription?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm bg-danger-soft text-danger"
                                                title="Delete Schedule">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted mb-3">
                                        <i class="fas fa-redo fa-3x mb-3 text-secondary opacity-50"></i>
                                        <p class="mb-0 fw-semibold">No recurring schedules registered.</p>
                                        <p class="fs-7">Schedule a service to connect buyers with templates and prices.</p>
                                    </div>
                                    <a href="{{ route('services.create') }}" class="btn btn-primary btn-sm">
                                        Create First Subscription
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($services->hasPages())
                <div class="px-4 py-3 border-top bg-light">
                    {{ $services->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection