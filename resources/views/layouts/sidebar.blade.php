<!-- resources/views/layouts/sidebar.blade.php -->
<style>
    .sidebar {
        position: fixed;
        top: 56px;
        left: 0;
        height: calc(100vh - 56px);
        width: 250px;
        background-color: white;
        transition: width 0.3s ease-in-out;
    }

    .sidebar-wrapper {
        height: 100%;
        overflow-y: auto;
    }

    .sidebar-toggler {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 1;
    }

    .sidebar.hide {
        width: 0;
    }

    .sidebar.show {
        width: 250px;
    }
</style>

<div class="sidebar hide">
    <div class="sidebar-wrapper">
        <div class="d-flex justify-content-between align-items-center p-2">
            <div class="d-flex align-items-center">
                <span class="hindi-font"> </span>
                <span class="ms-2"> Toggle Hindi</span>
            </div>
            <button class="navbar-toggler sidebar-toggler" type="button" data-bs-toggle="collapse" data-bs-target=".sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('reports.index') }}">
                    <i class="bi bi-file-text"></i> Reports
                </a>
            </li>
        </ul>
    </div>
</div>

<script>
    const sidebarToggle = document.querySelector('.sidebar-toggler');
    const sidebar = document.querySelector('.sidebar');

    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('show');
        sidebar.classList.toggle('hide');
    });
</script>
