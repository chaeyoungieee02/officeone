@extends('layouts.app')

@section('title', 'Products & Services - OfficeOne')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="bi bi-box-seam"></i> Products & Services</h2>
    <div>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New
        </a>
        <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-file-earmark-excel"></i> Import Excel
        </button>
    </div>
</div>

<!-- Filter Buttons -->
<div class="mb-3">
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-outline-primary active" id="btn-active" onclick="toggleTrashed(false)">
            <i class="bi bi-list-ul"></i> Active Records
        </button>
        <button type="button" class="btn btn-outline-danger" id="btn-trashed" onclick="toggleTrashed(true)">
            <i class="bi bi-trash"></i> Trashed Records
        </button>
    </div>
</div>

<!-- DataTable -->
<div class="card shadow-sm">
    <div class="card-body">
        <table id="products-table" class="table table-striped table-hover w-100">
            <thead class="table-dark">
                <tr>
                    <th>Photo</th>
                    <th>Item Code</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Unit</th>
                    <th>Unit Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

{{-- Import Errors --}}
@if(session('import_errors'))
<div class="alert alert-warning mt-3">
    <strong>Import Errors:</strong>
    <ul class="mb-0 mt-1">
        @foreach(session('import_errors') as $failure)
            <li>Row {{ $failure->row() }}: {{ implode(', ', $failure->errors()) }} ({{ $failure->attribute() }}: {{ $failure->values()[$failure->attribute()] ?? 'N/A' }})</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">
                        <i class="bi bi-file-earmark-excel"></i> Import Products from Excel
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">Select Excel File (.xlsx, .xls, .csv)</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                    </div>
                    <div class="alert alert-info">
                        <small>
                            <i class="bi bi-info-circle"></i>
                            Columns: Item Code, Name, Category (Product/Service), Unit, Unit Price, Description, Brand, Type, Active (1/0)
                        </small>
                    </div>
                    <a href="{{ route('products.template') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-download"></i> Download Template
                    </a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-upload"></i> Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let showTrashed = false;
    let table;

    $(document).ready(function () {
        table = $('#products-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("products.index") }}',
                data: function (d) {
                    d.show_trashed = showTrashed ? 1 : 0;
                }
            },
            columns: [
                { data: 'photo', name: 'photo', orderable: false, searchable: false },
                { data: 'item_code', name: 'item_code' },
                { data: 'name', name: 'name' },
                { data: 'category', name: 'category' },
                { data: 'brand', name: 'brand' },
                { data: 'unit', name: 'unit' },
                { data: 'formatted_price', name: 'unit_price' },
                { data: 'status', name: 'is_active', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[1, 'asc']],
            responsive: true,
            language: {
                emptyTable: "No products found."
            }
        });
    });

    function toggleTrashed(trashed) {
        showTrashed = trashed;
        if (trashed) {
            $('#btn-trashed').addClass('active');
            $('#btn-active').removeClass('active');
        } else {
            $('#btn-active').addClass('active');
            $('#btn-trashed').removeClass('active');
        }
        table.ajax.reload();
    }
</script>
@endpush
