<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
    @if (session('toast'))
        <div class="toast align-items-center text-bg-{{ session('toast')['type'] ?? 'success' }} border-0 show"
             role="alert" data-bs-autohide="true" data-bs-delay="4000">
            <div class="d-flex">
                <div class="toast-body">{{ session('toast')['message'] }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif
</div>
