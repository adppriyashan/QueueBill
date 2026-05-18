@extends('layouts.app')

@section('title', 'Companies Directory - QueueBill')
@section('page_title', 'Companies Directory Management')

@section('content')
<!-- Metrics Dashboard Cards -->
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card card-hover h-100 p-3 bg-white">
            <div class="d-flex align-items-center">
                <div class="rounded-3 p-3 bg-primary-soft text-primary me-3">
                    <i class="fas fa-building fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-secondary fw-semibold mb-1 fs-7">Total Companies</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ $totalCompanies }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card card-hover h-100 p-3 bg-white">
            <div class="d-flex align-items-center">
                <div class="rounded-3 p-3 bg-success-soft text-success me-3">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-secondary fw-semibold mb-1 fs-7">Active Directory</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ $activeCompanies }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card card-hover h-100 p-3 bg-white">
            <div class="d-flex align-items-center">
                <div class="rounded-3 p-3 bg-danger-soft text-danger me-3">
                    <i class="fas fa-times-circle fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-secondary fw-semibold mb-1 fs-7">Inactive Directory</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ $inactiveCompanies }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Header Controls -->
<div class="card mb-4 border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h5 class="fw-bold mb-1">Company Listing</h5>
                <p class="text-secondary mb-0 fs-7">Manage accounts, validation engines, and inline billing tracking</p>
            </div>
            <a href="{{ route('companies.create') }}" class="btn btn-primary d-inline-flex align-items-center">
                <i class="fas fa-plus me-2"></i>Add New Company
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
                        <th style="width: 25%">Company</th>
                        <th style="width: 25%">Email Address</th>
                        <th style="width: 20%">Phone</th>
                        <th style="width: 15%">Status</th>
                        <th style="width: 15%" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $company)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-ring bg-light text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 42px; height: 42px; font-weight: 700;">
                                        {{ strtoupper(substr($company->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('companies.show', $company) }}" class="fw-bold text-dark text-decoration-none d-block hover-primary">{{ $company->name }}</a>
                                        <span class="text-muted fs-8">Added {{ $company->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-medium">{{ $company->email }}</span>
                            </td>
                            <td>
                                <span class="text-secondary">{{ $company->phone ?? '—' }}</span>
                            </td>
                            <td>
                                @if($company->status === 'active')
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
                                            <a class="dropdown-item py-2" href="{{ route('companies.show', $company) }}">
                                                <i class="fas fa-history fa-fw me-2 text-primary"></i>Historical Profile
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-2" href="{{ route('companies.edit', $company) }}">
                                                <i class="fas fa-edit fa-fw me-2 text-warning"></i>Edit Details
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('companies.destroy', $company) }}" method="POST" onsubmit="return confirm('Are you sure you want to deactivate/delete this company?')">
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
                                    <i class="fas fa-building fa-3x mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-0 fw-semibold">No companies registered in directory.</p>
                                    <p class="fs-7">Register a company to manage billing cycles.</p>
                                </div>
                                <a href="{{ route('companies.create') }}" class="btn btn-primary btn-sm">
                                    Create First Company
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($companies->hasPages())
            <div class="px-4 py-3 border-top bg-light">
                {{ $companies->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
