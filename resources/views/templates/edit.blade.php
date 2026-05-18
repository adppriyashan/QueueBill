@extends('layouts.app')

@section('title', 'Update Structure Template - QueueBill')
@section('page_title', 'Edit Invoice Structure Template')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 p-2 bg-warning-soft text-warning me-3">
                            <i class="fas fa-edit fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Update Template: {{ $template->title }}</h5>
                            <p class="text-secondary mb-0 fs-7">Modify layout slugs, overrides, and activation flags.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('templates.update', $template) }}">
                        @csrf
                        @method('PUT')

                        <!-- Title Field -->
                        <div class="mb-4">
                            <label for="title" class="form-label text-secondary fw-semibold fs-7">Template Title <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                name="title" value="{{ old('title', $template->title) }}" required
                                placeholder="e.g. Web Hosting Corporate Template" autocomplete="off">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Slug Field -->
                        <div class="mb-4">
                            <label for="slug" class="form-label text-secondary fw-semibold fs-7">Dynamic URL Slug
                                parameter</label>
                            <div class="input-group">
                                <span class="input-group-text fs-8 text-secondary">/invoices/templates/</span>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug"
                                    name="slug" value="{{ old('slug', $template->slug) }}"
                                    placeholder="e.g. web-hosting-corporate" readonly>
                            </div>
                            @error('slug')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted fs-8">Auto-generated alphanumeric parameter via URLs. Changing
                                this updates dynamic config rendering pathways.</div>
                        </div>

                        <!-- Sender Email Override Field -->
                        <div class="mb-4">
                            <label for="sender_email" class="form-label text-secondary fw-semibold fs-7">Sender Email
                                Override (Optional)</label>
                            <input type="email" class="form-control @error('sender_email') is-invalid @enderror"
                                id="sender_email" name="sender_email"
                                value="{{ old('sender_email', $template->sender_email) }}"
                                placeholder="e.g. cloud-billing@corporate.com">
                            @error('sender_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted fs-8">Fallback system profile override field. If populated,
                                outgoing automation workflows bypass unified transactional configurations and broadcast
                                notifications cleanly using this configured alternative identity.</div>
                        </div>

                        <!-- Status Field -->
                        <div class="mb-4">
                            <label for="status" class="form-label text-secondary fw-semibold fs-7">Status <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                                required>
                                <option value="active" {{ old('status', $template->status) === 'active' ? 'selected' : '' }}>
                                    Active (Available for scheduling)</option>
                                <option value="inactive" {{ old('status', $template->status) === 'inactive' ? 'selected' : '' }}>Inactive (Unavailable)</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 border-top pt-4">
                            <a href="{{ route('templates.index') }}" class="btn btn-sm btn-light border">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Live Slug Auto-generation JS Helper
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');

        titleInput.addEventListener('input', function () {
            const slug = slugify(this.value);
            slugInput.value = slug;
        });

        function slugify(text) {
            return text.toString().toLowerCase()
                .replace(/\s+/g, '-')           // Replace spaces with -
                .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                .replace(/^-+/, '')             // Trim - from start of text
                .replace(/-+$/, '');            // Trim - from end of text
        }
    </script>
@endsection