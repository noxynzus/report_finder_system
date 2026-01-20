@extends('layouts.app')

@section('title', 'All Reports')

@section('content')
<div class="row mb-4">
    <div class="col-lg-8 mx-auto">
        <div class="text-center mb-4">
            <h1 class="display-4 text-white fw-bold mb-3">Report Management</h1>
            <p class="lead text-white opacity-75">Upload, manage, and share your PDF reports</p>
        </div>

        <!-- Search & Upload -->
        <div class="card mb-4">
            <div class="card-body p-4">
                <form action="{{ route('reports.index') }}" method="GET" class="row g-3">
                    <div class="col-md-8">
                        <div class="search-box d-flex align-items-center">
                            <i class="bi bi-search text-muted me-2"></i>
                            <input 
                                type="text" 
                                name="search" 
                                class="form-control border-0" 
                                placeholder="Search reports..." 
                                value="{{ request('search') }}"
                            >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-gradient w-100">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </form>

                <div class="mt-3">
                    <button 
                        class="btn btn-gradient w-100" 
                        data-bs-toggle="modal" 
                        data-bs-target="#uploadModal"
                    >
                        <i class="bi bi-cloud-upload"></i> Upload New Report
                    </button>
                </div>
            </div>
        </div>

        <!-- Category Filters -->
        @if($categories->isNotEmpty())
        <div class="card mb-4">
            <div class="card-body">
                <div class="filter-pills text-center">
                    <a href="{{ route('reports.index') }}" 
                       class="btn btn-sm {{ !request('category') ? 'btn-gradient' : 'btn-outline-secondary' }}">
                        All
                    </a>
                    @foreach($categories as $cat)
                    <a href="{{ route('reports.index', ['category' => $cat]) }}" 
                       class="btn btn-sm {{ request('category') === $cat ? 'btn-gradient' : 'btn-outline-secondary' }}">
                        {{ $cat }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Reports Grid -->
<div class="row">
    @forelse($reports as $report)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card report-card h-100">
            <div class="report-card-body">
                <div class="report-icon">
                    <i class="bi bi-file-earmark-pdf"></i>
                </div>
                
                <h5 class="card-title text-center mb-2">{{ $report->title }}</h5>
                
                @if($report->description)
                <p class="card-text text-muted small text-center mb-3">
                    {{ Str::limit($report->description, 80) }}
                </p>
                @endif

                @if($report->category)
                <div class="text-center mb-3">
                    <span class="badge bg-primary badge-custom">
                        {{ $report->category }}
                    </span>
                </div>
                @endif

                <div class="text-center text-muted small mb-3">
                    <div><i class="bi bi-person"></i> {{ $report->uploader->name }}</div>
                    <div><i class="bi bi-calendar"></i> {{ $report->created_at->format('M d, Y') }}</div>
                    <div><i class="bi bi-download"></i> {{ $report->download_count }} downloads</div>
                    <div><i class="bi bi-file-earmark"></i> {{ $report->formatted_file_size }}</div>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('reports.preview', $report) }}" 
                       target="_blank"
                       class="btn btn-outline-primary">
                        <i class="bi bi-eye"></i> Preview
                    </a>
                    <a href="{{ route('reports.download', $report) }}" 
                       class="btn btn-gradient">
                        <i class="bi bi-download"></i> Download
                    </a>
                    
                    @if(auth()->id() === $report->uploaded_by)
                    <form action="{{ route('reports.destroy', $report) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this report?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h3 class="text-muted">No Reports Found</h3>
                    <p class="text-muted">Upload your first report to get started!</p>
                    <button class="btn btn-gradient mt-3" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="bi bi-cloud-upload"></i> Upload Report
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($reports->hasPages())
<div class="row mt-4">
    <div class="col-12">
        <div class="d-flex justify-content-center">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endif

<!-- Upload Modal -->
<div class="modal fade upload-modal" id="uploadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="bi bi-cloud-upload"></i> Upload New Report
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" placeholder="e.g., Financial, Technical">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">PDF File <span class="text-danger">*</span></label>
                        <div class="upload-area" id="uploadArea">
                            <input 
                                type="file" 
                                name="file" 
                                id="fileInput" 
                                class="d-none" 
                                accept=".pdf" 
                                required
                            >
                            <i class="bi bi-cloud-upload display-4 text-muted"></i>
                            <p class="mt-3 mb-0">Drag & drop or click to upload</p>
                            <small class="text-muted">PDF files only (Max 10MB)</small>
                            <div id="fileName" class="mt-2 text-primary"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient">
                        <i class="bi bi-upload"></i> Upload Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    const fileName = document.getElementById('fileName');

    // Click to upload
    uploadArea.addEventListener('click', () => fileInput.click());

    // File input change
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            fileName.textContent = this.files[0].name;
        }
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragging');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragging');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragging');
        
        const files = e.dataTransfer.files;
        if (files.length > 0 && files[0].type === 'application/pdf') {
            fileInput.files = files;
            fileName.textContent = files[0].name;
        }
    });
});
</script>
@endpush