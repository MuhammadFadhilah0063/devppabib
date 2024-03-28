<form id="deleteForm_" action="{{ $jobsite }}/{{ $model->id }}/ubah/status" method="post" class="d-inline">
    @csrf
    @method('PUT')
    <button class="btn fw-bold btn-sm btn-{{ $model->status == 'Tidak Aktif' ? 'danger' : 'success' }} d-inline"
        type="submit">
        {{ $model->status }}
    </button>
</form>
