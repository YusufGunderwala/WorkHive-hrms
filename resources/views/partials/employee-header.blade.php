<div
    class="glass-card mb-4 animate__animated animate__fadeInDown p-4 d-flex justify-content-between align-items-center">
    <div>
        <h2 class="fw-bold text-dark mb-1">{{ $title }}</h2>
        <p class="text-muted mb-0">{{ $description }}</p>
    </div>

    <div class="d-flex align-items-center gap-3">
        <!-- Unified Time & Announcement Card -->
        <div class="px-3 py-2 d-flex align-items-center gap-4">
            {{-- Clock Section --}}
            <div class="d-flex align-items-center gap-3 border-end pe-4">
                <i class="fas fa-clock text-secondary fa-2x opacity-50"></i>
                <span class="fw-bold font-monospace text-dark fs-4" id="live-clock">{{ now()->format('H:i:s') }}</span>
            </div>

            {{-- Announcement Section (Latest only) --}}
            @if(isset($announcements) && $announcements->count() > 0)
                @php $latest = $announcements->first(); @endphp
                <div class="d-flex align-items-center gap-3 animate__animated animate__fadeIn" style="max-width: 400px;">
                    <i class="fas fa-bullhorn text-warning fa-2x"></i>
                    <div class="text-truncate">
                        <span class="fw-bold text-dark fs-5">Meeting:</span>
                        <span class="text-secondary fs-5">{{ $latest->title }}</span>
                    </div>
                </div>
            @else
                <div class="d-flex align-items-center gap-3 opacity-50">
                    <i class="fas fa-bullhorn text-secondary fa-2x"></i>
                    <span class="text-muted">No announcements</span>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Global Live Clock Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (!document.getElementById('live-clock-initialized')) {
            const clockEl = document.getElementById('live-clock');
            clockEl.id = 'live-clock-initialized'; // Prevent double init
            setInterval(() => {
                const now = new Date();
                clockEl.innerText = now.toLocaleTimeString();
            }, 1000);
        }
    });
</script>