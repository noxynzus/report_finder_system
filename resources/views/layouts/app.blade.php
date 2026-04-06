<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Report Management System')</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom Style -->
    <!-- Custom Style -->
    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #7c3aed;
            --accent-color: #ec4899;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .layout {
            width: 100%;
            display: grid;
            grid:
                "header header header" auto "leftSide body body" 1fr "footer footer footer" auto / auto 1fr auto;
            /* gap: 8px; */
        }

        .navbar {
            grid-area: header;
        }

        .sidebar {
            grid-area: leftSide;
        }

        .body {
            grid-area: body;
        }

        .footer {
            grid-area: footer;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 1rem 0;
            color: #6b7280;
            font-size: 0.875rem;
            min-height: 64px;
        }

        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: rgba(255, 255, 255, 0.95);
            color: #111827;
        }

        .sidebar a {
            color: #111827;
            padding: 0.75rem 1.25rem;
            display: block;
            text-decoration: none;
            border-radius: 8px;
        }

        .sidebar a:hover {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        /* Card */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-weight: 600;
            border-radius: 15px 15px 0 0;
        }

        .btn-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
        }

        .btn-gradient:hover {
            box-shadow: 0 5px 15px rgba(79, 70, 229, 0.4);
            transform: translateY(-2px);
        }

        /* Content wrapper */
        .content-wrapper {
            min-height: calc(100vh - 56px);
            padding: 1.5rem;
        }
    </style>
    @stack('styles')
</head>

<body>

    <section class="layout">
        <!-- Navbar -->
        <nav class="header navbar navbar-expand-lg fixed-top">
            <div class="container-fluid">
                <button class="btn btn-outline-secondary d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
                    <i class="bi bi-list"></i>
                </button>

                <a class="navbar-brand ms-2" href="{{ route('reports.index') }}">
                    <i class="bi bi-file-earmark-pdf"></i> @yield('page-title', 'Report Manager')
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="ms-auto d-flex align-items-center gap-2">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reports.index') }}">
                                <i class="bi bi-grid"></i> All Reports
                            </a>
                        </li>
                        @auth
                        <li class="nav-item">
                            <span class="nav-link" href="{{route('profile.edit') }}">
                                <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                            </span>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                        @endauth
                    </ul>
                    <span class="text-muted small">{{ Auth::user()->name ?? 'Guest' }}</span>
                    <img src="https://ui-avatars.com/api/?name=User" class="rounded-circle" width="36">
                </div>
            </div>
        </nav>

        <!-- Sidebar -->
        <div class="sidebar d-lg-block hide h-100 overflow-x-auto ">
            <!-- Sidebar (Desktop) -->
            <aside class="col-lg-2 d-none d-lg-block sidebar vh-100 pt-4 mt-5">
                <a href="#"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                <a href="#"><i class="bi bi-file-earmark-text me-2"></i> Reports</a>
                <a href="#"><i class="bi bi-people me-2"></i> Users</a>
                <a href="#"><i class="bi bi-gear me-2"></i> Settings</a>
            </aside>

            <!-- Sidebar (Mobile Offcanvas) -->
            <div class="offcanvas offcanvas-start sidebar" tabindex="-1" id="sidebar">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title">Menu</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">
                    <a href="#">Dashboard</a>
                    <a href="#">Reports</a>
                    <a href="#">Users</a>
                    <a href="#">Settings</a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="body container-fluid mt-[70px] d-flex flex-column h-screen align-items-center">
            <div class="col-10 content-wrapper">@yield('content')</div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <footer class="container-fluid bg-white">
                asdasdad

            </footer>
        </div>
    </section>


    <script>
        document.getElementById('menu-btn').addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
        });
    </script>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>